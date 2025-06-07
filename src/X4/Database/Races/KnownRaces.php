<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Races;

class KnownRaces
{
    private RaceDefs $defs;

    private function __construct()
    {
        $this->defs = RaceDefs::getInstance();
    }

    private static ?KnownRaces $instance = null;

    public static function getInstance() : KnownRaces
    {
        if (!isset(self::$instance)) {
            self::$instance = new KnownRaces();
        }

        return self::$instance;
    }

    public function getArgon() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_ARGON);
    }

    public function getParanid() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_PARANID);
    }

    public function getTeladi() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_TELADI);
    }

    public function getAntigone() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_ANTIGONE);
    }

    public function getGeneric() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_GENERIC);
    }

    public function getXenon() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_XENON);
    }

    public function getBoron() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_BORON);
    }

    public function getPirate() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_PIRATE);
    }

    public function getSplit() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_SPLIT);
    }

    public function getTerran() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_TERRAN);
    }

    public function getYaki() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_YAKI);
    }

    public function getAtf() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_ATF);
    }

    public function getKhaak() : RaceDef
    {
        return $this->defs->getByID(RaceDefs::RACE_KHAAK);
    }
}
