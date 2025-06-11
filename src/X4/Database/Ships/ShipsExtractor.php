<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;

class ShipsExtractor
{
    private array $ships = array();

    public function extract() : void
    {
        $this->extractShips();
        $this->validateShipClasses();
    }

    private function extractShips() : void
    {
        Console::header('Extracting ships');

        foreach (WareDefs::getInstance()->getAll() as $ware) {
            $this->processWare($ware);
        }

        Console::line1('Found [%d] ships.', count($this->ships));
        Console::line1('Writing ships to file.');
        Console::nl();

        ShipDefs::getInstance()
            ->getDataFile()
            ->putData($this->ships);
    }

    private function validateShipClasses() : void
    {
        Console::header('Validating ship classes');

        $collection = ShipClasses::getInstance();

        $usedClasses = array();
        foreach($this->ships as $ship) {
            $usedClasses[] = $ship[ShipDef::KEY_CLASS_ID];
        }

        foreach($collection->getIDs() as $id) {
            if(!in_array($id, $usedClasses)) {
                Console::line1('ERROR | The ship class [%s] is not used by any ship.', $id);
                exit;
            }
        }

        Console::line1('Done.');
        Console::nl();
    }

    private function processWare(WareDef $def) : void
    {
        if($def->getGroupID() !== WareGroups::GROUP_SHIPS) {
            return;
        }

        $dom = MacroFileDefs::getInstance()->getByID($def->getMacroID())->getDOM();
        $shipID = $def->getID();

        $alias = $this->resolveParentMacro($dom);

        $domAlias = null;
        if(!empty($alias)) {
            $domAlias = MacroFileDefs::getInstance()->getByID($alias)->getDOM();
        }

        $this->ships[] = array(
            ShipDef::KEY_ID => $shipID,
            ShipDef::KEY_DATA_SOURCE_ID => $def->getDataSourceID(),
            ShipDef::KEY_SIZE => $this->resolveShipSize($dom),
            ShipDef::KEY_CLASS_ID => $this->resolveShipClass($domAlias ?? $dom, $shipID),
            ShipDef::KEY_BUILDER_FACTION_ID => $this->resolveFaction($domAlias ?? $dom, $shipID),
            ShipDef::KEY_USED_BY => $def->getFactionIDs()
        );
    }

    private function resolveShipClass(DOMExtended $dom, string $shipID) : string
    {
        $class = $dom
            ->byTagName('ship')
            ->requireFirst(sprintf('ERROR | No ship tag found for ship macro [%s].', $shipID))
            ->getAttribute('type');

        if(ShipClasses::getInstance()->idExists($class)) {
            return $class;
        }

        Console::line1('ERROR | The ship class [%s] is not known.', $class);
        exit;
    }

    private function resolveParentMacro(DOMExtended $dom) : ?string
    {
        $alias = $dom->byTagName('macro')->requireFirst()->getAttribute('alias');
        if(!empty($alias)) {
            return $alias;
        }

        return null;
    }

    private function resolveFaction(DOMExtended $dom, string $shipID) : string
    {
        return $dom
            ->byTagName('identification')
            ->requireFirst(sprintf('ERROR | No identification element found for ship macro [%s].', $shipID))
            ->getAttribute('makerrace');
    }

    private function resolveShipSize(DOMExtended $dom) : string
    {
        $class = $dom->byTagName('macro')->requireFirst()->getAttribute('class');
        $parts = explode('_', $class);
        return strtolower(array_pop($parts));
    }
}
