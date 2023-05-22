<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints;

use Mistralys\X4\Database\Races\RaceDef;

abstract class BlueprintDef
{
    private string $id;
    private BlueprintCategory $category;
    private ?RaceDef $race;
    private string $label;

    public function __construct(BlueprintCategory $category, string $id, RaceDef $race)
    {
        $this->category = $category;
        $this->id = $id;
        $this->label = $id;
        $this->race = $race;
    }

    public function getID() : string
    {
        return $this->id;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    abstract public function getTypeLabel() : string;

    /**
     * @return BlueprintCategory
     */
    public function getCategory(): BlueprintCategory
    {
        return $this->category;
    }

    public function getCategoryID() : string
    {
        return $this->category->getID();
    }

    /**
     * @return string
     * @deprecated Use {@see self::getID()} instead.
     */
    public function getName(): string
    {
        return $this->id;
    }

    public function getRace() : ?RaceDef
    {
        return $this->race;
    }

    public function getRaceID() : string
    {
        return $this->race->getID();
    }
}
