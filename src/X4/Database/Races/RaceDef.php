<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Races;

class RaceDef
{
    private string $id;
    private string $label;

    public function __construct(string $raceID, string $label)
    {
        $this->id = $raceID;
        $this->label = $label;
    }

    /**
     * @return string Lowercase identifier, e.g. "arg" or "par"
     */
    public function getID() : string
    {
        return $this->id;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function isGeneric() : bool
    {
        return $this->getID() === RaceDefs::RACE_GENERIC;
    }
}
