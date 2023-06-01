<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use Mistralys\X4\Database\Races\RaceDef;
use Mistralys\X4\Database\Races\RaceDefs;
use Mistralys\X4\Database\Races\RaceException;

class ModuleDef
{
    public const ERROR_COULD_NOT_DETECT_RACE = 106201;

    private string $id;
    private string $label;
    private ?RaceDef $race;
    private ModuleCategory $category;

    public function __construct(string $moduleID, string $label, ModuleCategory $category)
    {
        $this->id = $moduleID;
        $this->label = $label;
        $this->category = $category;
    }

    public function getID() : string
    {
        return $this->id;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getCategory() : ModuleCategory
    {
        return $this->category;
    }

    /**
     * @return RaceDef
     * @throws ModuleException
     * @throws RaceException
     */
    public function getRace() : RaceDef
    {
        if(isset($this->race))
        {
            return $this->race;
        }

        $parts = explode('_', $this->id);
        $races = RaceDefs::getInstance();

        foreach($parts as $part)
        {
            if($races->idExists($part))
            {
                $race = $races->getByID($part);
                $this->race = $race;
                return $race;
            }
        }

        throw new ModuleException(
            'Cannot detect race of module.',
            sprintf(
                'The race could not be detected from module ID [%s].',
                $this->id
            ),
            self::ERROR_COULD_NOT_DETECT_RACE
        );
    }

    public function isProduction() : bool
    {
        return $this->getCategory()->isProduction();
    }
}
