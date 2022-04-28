<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Races;

class RaceDefs
{
    public const ERROR_UNKNOWN_RACE_ID = 106301;

    private static ?RaceDefs $instance = null;

    /**
     * @var array<string,Race>
     */
    private static array $races = array();

    private function __construct()
    {
        $this
            ->add('arg', 'Argon')
            ->add('par', 'Paranid')
            ->add('tel', 'Teladi')
            ->add('ant', 'Antigone')
            ->add('gen', 'Generic')
            ->add('xen', 'Xenon');

        uasort(self::$races, static function(Race $a, Race $b) {
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

    private function add(string $id, string $label) : self
    {
        self::$races[$id] = new Race($id, $label);
        return $this;
    }

    /**
     * @return Race[]
     */
    public function getAll() : array
    {
        return array_values(self::$races);
    }

    public function idExists(string $id) : bool
    {
        return isset(self::$races[strtolower($id)]);
    }

    /**
     * @param string $id
     * @return Race
     * @throws RaceException
     */
    public function getByID(string $id) : Race
    {
        $id = strtolower($id);

        if(isset(self::$races[$id]))
        {
            return self::$races[$id];
        }

        throw new RaceException(
            'Unknown race ID.',
            sprintf(
                'Unknown race ID [%s]. Known race IDs are: [%s].',
                $id,
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
        $ids = array_keys(self::$races);
        sort($ids);
        return $ids;
    }
}
