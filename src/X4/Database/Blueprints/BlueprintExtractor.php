<?php

declare(strict_types=1);

namespace Mistral\X4\Database\Blueprints;

use AppUtils\FileHelper\FolderInfo;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\Database\Blueprints\BlueprintDef;
use Mistralys\X4\Database\Blueprints\BlueprintDefs;
use Mistralys\X4\Database\Blueprints\Categories\Types\CountermeasureCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\DeployableCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\DroneCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\EngineCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\MineCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\MissileCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\ModuleCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\ShieldCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\ShipCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\ThrusterCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\TurretCategory;
use Mistralys\X4\Database\Blueprints\Categories\Types\WeaponCategory;
use Mistralys\X4\Database\Builder\KnownItemsClassGenerator;
use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;
use Mistralys\X4\UI\Console;
use Mistralys\X4\X4Application;
use function AppLocalize\t;

class BlueprintExtractor
{
    public function extract() : void
    {
        foreach (WareDefs::getInstance()->getAll() as $ware) {
            $this->processWare($ware);
        }

        ksort($this->blueprints);

        JSONFile::factory(X4Application::getDataFolder() .'/blueprints.json')
            ->setPrettyPrint(true)
            ->setTrailingNewline(true)
            ->putData(array_values($this->blueprints));

        $this->writeKnownBlueprintsClass();

        print_r($this->summary);
    }

    /**
     * @var string[]
     */
    private array $excludeTags = array(
        'noblueprint',
        'noplayerblueprint',
        'noplayerbuild'
    );

    private array $summary = array();

    private function processWare(WareDef $def) : void
    {
        foreach($this->excludeTags as $tag) {
            if($def->hasTag($tag)) {
                Console::line2('Ware [%s] | SKIP | Excluded by tag [%s]', $def->getID(), $tag);
                return;
            }
        }

        // Must have a component ID to be buildable.
        if(empty($def->getMacroID())) {
            Console::line2('Ware [%s] | SKIP | No component ID', $def->getID());
            return;
        }

        if($def->getGroupID() === WareGroups::GROUP_OTHER) {
            Console::line2('Ware [%s] | SKIP | Group is "Other"', $def->getID());
            return;
        }

        $parts = explode('_', $def->getID());
        $type = array_shift($parts);
        $categoryID = $this->detectCategoryClass($def);
        $wareID = $def->getID();

        if($categoryID === null) {
            $text = $def->getGroupID().' = '.$categoryID.' ('.implode(', ', $def->getTags()).')'.PHP_EOL;

            if(!in_array($text, $this->summary)) {
                $this->summary[] = $text;
            }

            Console::line2('Ware [%s] | SKIP | Unknown type [%s]', $wareID, $type);
            return;
        }

        $this->blueprints[$wareID] = array(
            BlueprintDef::KEY_WARE_ID => $wareID,
            BlueprintDef::KEY_LABEL => $def->getLabel(),
            BlueprintDef::KEY_VARIANT_ID => (string)VariantID::resolveWareVariantID($wareID),
            BlueprintDef::KEY_CATEGORY_ID => $categoryID
        );
    }

    /**
     * @var array<string, string>
     */
    private array $categories = array(
        'engine' => EngineCategory::CATEGORY_ID,
        'countermeasure' => CountermeasureCategory::CATEGORY_ID,
        'missile' => MissileCategory::CATEGORY_ID,
        'module' => ModuleCategory::CATEGORY_ID,
        'resourceprobe' => DeployableCategory::CATEGORY_ID,
        'satellite' => DeployableCategory::CATEGORY_ID,
        'shield' => ShieldCategory::CATEGORY_ID,
        'weapon' => WeaponCategory::CATEGORY_ID,
        'navbeacon' => DeployableCategory::CATEGORY_ID,
        'turret' => TurretCategory::CATEGORY_ID,
        'ship' => ShipCategory::CATEGORY_ID,
        'drone' => DroneCategory::CATEGORY_ID,
        'lasertower' => DeployableCategory::CATEGORY_ID,
        'thruster' => ThrusterCategory::CATEGORY_ID,
        'mine' => MineCategory::CATEGORY_ID,
    );

    private function detectCategoryClass(WareDef $def) : ?string
    {
        foreach($this->categories as $tag => $category) {
            if($def->hasTag($tag)) {
                return $category;
            }
        }

        return null;
    }

    /**
     * @var array<string, array<string, string>>
     */
    private array $blueprints = array();

    private function writeKnownBlueprintsClass() : void
    {
        $generator = new KnownItemsClassGenerator(
            BlueprintDefs::class,
            BlueprintDef::class,
            FolderInfo::factory(__DIR__)
        );

        foreach($this->blueprints as $blueprint) {
            $generator->addItem(
                $blueprint[BlueprintDef::KEY_WARE_ID],
                $blueprint[BlueprintDef::KEY_LABEL],
                VariantID::fromID($blueprint[BlueprintDef::KEY_VARIANT_ID])
            );
        }

        $generator->setDescription(t('This class contains constants and getter methods for all known blueprints.'));

        $generator->generate();
    }
}
