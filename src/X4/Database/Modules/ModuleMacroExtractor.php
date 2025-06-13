<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Modules;

use AppUtils\ConvertHelper;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\XML\DOMExtended;
use Mistralys\X4\XML\ElementExtended;

class ModuleMacroExtractor
{
    private WareDef $ware;
    private DOMExtended $dom;

    public function __construct(WareDef $ware)
    {
        $this->ware = $ware;
        $this->dom = $ware->getMacro()->getDOM();
    }

    /**
     * Extracts the module data from the macro XML.
     *
     * @return array<string,string|int|null>
     */
    public function extract() : array
    {
        return array(
            ModuleDef::KEY_WARE_ID => $this->ware->getID(),
            ModuleDef::KEY_MACRO_ID => $this->dom->byTagName('macro')->requireFirst()->getAttribute('name'),
            ModuleDef::KEY_LABEL => $this->ware->getLabel(),
            ModuleDef::KEY_CATEGORY => $this->dom->byTagName('macro')->requireFirst()->getAttribute('class'),
            ModuleDef::KEY_SIZE => $this->resolveSize(),
            ModuleDef::KEY_BUILDER_FACTION_ID => $this->resolveFactionID(),
            ModuleDef::KEY_HULL => $this->resolveHull(),
            ModuleDef::KEY_REQUIRED_WORKFORCE => $this->resolveWorkforce(),
            ModuleDef::KEY_HOUSING_CAPACITY => $this->resolveHousingCapacity(),
            ModuleDef::KEY_HOUSING_FACTION_ID => $this->resolveHousingFactionID(),
            ModuleDef::KEY_CARGO_CAPACITY => $this->resolveCargoCapacity(),
            ModuleDef::KEY_CARGO_TYPE => $this->resolveCargoType(),
            ModuleDef::KEY_DRONE_CAPACITY => $this->resolveDroneCapacity(),
            ModuleDef::KEY_WARES_PRODUCED => $this->resolveProduction(),
            ModuleDef::KEY_VARIANT_ID => (string)$this->ware->getVariantID(),
        );
    }

    private function resolveProduction() : ?array
    {
        $el = $this->dom->byTagName('production')->getFirst();
        if($el !== null) {
            return ConvertHelper::explodeTrim(' ', $el->getAttribute('wares'));
        }

        return null;
    }

    private function resolveDroneCapacity() : int
    {
        $el = $this->dom->byTagName('storage')->getFirst();
        if($el !== null) {
            return (int)$el->getAttribute('unit');
        }

        return 0;

    }

    private function resolveCargoType() : ?string
    {
        $el = $this->dom->byTagName('cargo')->getFirst();
        if($el !== null) {
            return $el->getAttribute('tags');
        }

        return null;
    }

    private function resolveCargoCapacity() : int
    {
        $el = $this->dom->byTagName('cargo')->getFirst();
        if($el !== null) {
            return (int)$el->getAttribute('max');
        }

        return 0;
    }

    private function resolveWorkforce() : int
    {
        $el = $this->dom->byTagName('workforce')->getFirst();
        if($el) {
            return (int)$el->getAttribute('max');
        }

        return 0;
    }

    private function resolveHousingCapacity() : int
    {
        $el = $this->dom->byTagName('workforce')->getFirst();
        if($el) {
            return (int)$el->getAttribute('capacity');
        }

        return 0;
    }

    private function resolveHousingFactionID() : ?string
    {
        $el = $this->dom->byTagName('workforce')->getFirst();
        if($el === null) {
            return null;
        }

        $faction = $el->getAttribute('race');
        if(!empty($faction)) {
            return $faction;
        }

        return null;
    }

    private function resolveHull() : int
    {
        $el = $this->dom->byTagName('hull')->getFirst();
        if($el !== null) {
            return (int)$el->getAttribute('max');
        }

        return 0;
    }

    private function resolveSize() : ?string
    {
        $size = $this->tagIdentification()->getAttribute('size');
        if(!empty($size)) {
            return $size;
        }

        return null;
    }

    private function resolveFactionID() : ?string
    {
        $faction = $this->tagIdentification()->getAttribute('makerrace');
        if(!empty($faction)) {
            return $faction;
        }

        return FactionDefs::getInstance()->detectFactionByID($this->ware->getID());
    }

    private function tagIdentification() : ElementExtended
    {
        return $this->dom->byTagName('identification')->requireFirst();
    }
}