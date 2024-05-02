<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Races;

class RaceDefs
{
    public const ERROR_UNKNOWN_RACE_ID = 106301;

    public const RACE_ARGON = 'arg';
    public const RACE_PARANID = 'par';
    public const RACE_TELADI = 'tel';
    public const RACE_ANTIGONE = 'ant';
    public const RACE_GENERIC = 'gen';
    public const RACE_XENON = 'xen';
    public const RACE_BORON = 'bor';
    public const RACE_PIRATE = 'pir';
    public const RACE_SPLIT = 'spl';
    public const RACE_TERRAN = 'ter';
    public const RACE_YAKI = 'yak';
    public const RACE_ATF = 'atf';
    public const RACE_KHAAK = 'kha';

    private static ?RaceDefs $instance = null;

    /**
     * @var array<string,RaceDef>
     */
    private static array $defs = array();

    private function __construct()
    {
        $this
            ->add(self::RACE_ARGON, 'Argon')
            ->add(self::RACE_PARANID, 'Paranid')
            ->add(self::RACE_TELADI, 'Teladi')
            ->add(self::RACE_ANTIGONE, 'Antigone')
            ->add(self::RACE_GENERIC, 'Generic')
            ->add(self::RACE_XENON, 'Xenon')
            ->add(self::RACE_BORON, 'Boron')
            ->add(self::RACE_PIRATE, 'Pirates')
            ->add(self::RACE_TERRAN, 'Terran')
            ->add(self::RACE_SPLIT, 'Split')
            ->add(self::RACE_YAKI, 'Yaki')
            ->add(self::RACE_ATF, 'ATF')
            ->add(self::RACE_KHAAK, 'Kha\'ak');

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
