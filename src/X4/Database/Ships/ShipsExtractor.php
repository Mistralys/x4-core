<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use AppUtils\FileHelper\FolderInfo;
use Mistralys\X4\Database\Builder\KnownItemsClassGenerator;
use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\DatabaseBuilder;
use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;
use Mistralys\X4\UI\Console;
use Mistralys\X4\XML\DOMExtended;
use function AppUtils\array_remove_values;

class ShipsExtractor
{
    /**
     * @var array<string,array<string,mixed>>
     */
    private array $ships = array();

    public function extract() : void
    {
        $this->extractShips();
        $this->validateShipClasses();
        $this->generateKnownShipsClass();
    }

    private function extractShips() : void
    {
        Console::header('Extracting ships');

        foreach (WareDefs::getInstance()->getAll() as $ware) {
            $this->processWare($ware);
        }

        ksort($this->ships);

        $this->validateVariants();

        Console::line1('Found [%d] ships.', count($this->ships));
        Console::line1('Writing ships to file.');
        Console::nl();

        ShipDefs::getInstance()
            ->getDataFile()
            ->putData(array_values($this->ships));
    }

    private function validateVariants() : void
    {
        $labels = array();
        foreach($this->ships as $ship) {
            if(!isset($labels[$ship[ShipDef::KEY_LABEL]])) {
                $labels[$ship[ShipDef::KEY_LABEL]] = array();
            }

            $labels[$ship[ShipDef::KEY_LABEL]][] = $ship[ShipDef::KEY_WARE_ID];
        }

        foreach($labels as $label => $ids) {
            if(count($ids) === 1) {
                continue;
            }

            Console::line1('NOTICE | The ship [%s] has multiple variants.', $label);

            foreach($ids as $id) {
                $this->ships[$id][ShipDef::KEY_VARIANTS] = array_values(array_remove_values($ids, array($id)));
            }
        }
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

        $this->ships[$shipID] = array(
            ShipDef::KEY_WARE_ID => $shipID,
            ShipDef::KEY_LABEL => $def->getLabel(),
            ShipDef::KEY_VARIANT_ID => (string)VariantID::resolveWareVariantID($shipID),
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

    private function generateKnownShipsClass() : void
    {
        $generator = new KnownItemsClassGenerator(
            ShipDefs::class,
            ShipDef::class,
            FolderInfo::factory(__DIR__)
        );

        foreach($this->ships as $item)
        {
            $generator->addItem(
                $item[ShipDef::KEY_WARE_ID],
                $item[ShipDef::KEY_LABEL],
                VariantID::fromID($item[ShipDef::KEY_VARIANT_ID])
            );
        }

        $generator->generate();
    }
}
