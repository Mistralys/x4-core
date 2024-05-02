<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use AppUtils\ArrayDataCollection;
use Mistralys\X4\Database\Races\RaceDef;
use Mistralys\X4\Database\Races\RaceDefs;
use Mistralys\X4\Database\Races\RaceException;

class ModuleDef
{
    public const ERROR_COULD_NOT_DETECT_RACE = 106201;

    private string $id;
    private ?RaceDef $race;
    private ModuleCategory $category;
    private ArrayDataCollection $data;

    public function __construct(string $moduleID, ModuleCategory $category, array $data)
    {
        $this->id = $moduleID;
        $this->data = ArrayDataCollection::create($data);
        $this->category = $category;
    }

    public function getID() : string
    {
        return $this->id;
    }

    public function getLabel() : string
    {
        $label = $this->data->getString(ModuleExtractor::KEY_LABEL);

        if(!empty($label))
        {
            return $label;
        }

        return $this->getID();
    }

    public function getMacro() : string
    {
        return $this->data->getString(ModuleExtractor::KEY_MACRO);
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
            sprintf('Cannot detect race of module [%s].', $this->id),
            '',
            self::ERROR_COULD_NOT_DETECT_RACE
        );
    }

    public function isProduction() : bool
    {
        return $this->getCategory()->isProduction();
    }
}
