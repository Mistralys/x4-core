<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Races;

class RaceDefs
{
    public const ERROR_UNKNOWN_RACE_ID = 106301;

    private static ?RaceDefs $instance = null;

    /**
     * @var array<string,RaceDef>
     */
    private static array $defs = array();

    private function __construct()
    {
        $this
            ->add('arg', 'Argon')
            ->add('par', 'Paranid')
            ->add('tel', 'Teladi')
            ->add('ant', 'Antigone')
            ->add('gen', 'Generic')
            ->add('xen', 'Xenon');

        uasort(self::$defs, static function(RaceDef $a, RaceDef $b) {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });
    }

    public static function getInstance() : RaceDefs
    {
        if(isset(self::$instance))
        {
            return self::$instance;
        }

        $defs = new RaceDefs();
        self::$instance = $defs;
        return $defs;
    }

    private function add(string $raceID, string $label) : self
    {
        self::$defs[$raceID] = new RaceDef($raceID, $label);
        return $this;
    }

    /**
     * @return RaceDef[]
     */
    public function getAll() : array
    {
        return array_values(self::$defs);
    }

    public function idExists(string $raceID) : bool
    {
        return isset(self::$defs[strtolower($raceID)]);
    }

    /**
     * @param string $raceID
     * @return RaceDef
     * @throws RaceException
     */
    public function getByID(string $raceID) : RaceDef
    {
        $raceID = strtolower($raceID);

        if(isset(self::$defs[$raceID]))
        {
            return self::$defs[$raceID];
        }

        throw new RaceException(
            'Unknown race ID.',
            sprintf(
                'Unknown race ID [%s]. Known race IDs are: [%s].',
                $raceID,
                implode(', ', $this->getIDs())
            ),
            self::ERROR_UNKNOWN_RACE_ID
        );
    }

    /**
     * @return string[]
     */
    public function getIDs() : array
    {
        $ids = array_keys(self::$defs);
        sort($ids);
        return $ids;
    }
}
