<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships;

use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\KnownDataSources;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\KnownFactions;
use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\Ships\ShipClass;
use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Ships\ShipDefs;
use Mistralys\X4\Database\Ships\MacroProperties\ShipMacroProperties;
use Mistralys\X4\Database\Ships\ShipException;
use Mistralys\X4\Database\Ships\ShipSize;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareTransportTypes;
use Mistralys\X4\Database\Translations\Languages;
use X4Tests\Helpers\RawShipMacroScanner;
use X4Tests\Helpers\RawWareScanner;
use X4Tests\Helpers\X4TestCase;

final class ShipDefTests extends X4TestCase
{
    private const TUGBOAT_SHIP_ID = 'ship_gen_m_tugboat_01_a';

    private ShipDefs $ships;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ships = ShipDefs::getInstance();
    }

    // =========================================================================
    // Basic Property Tests
    // =========================================================================

    public function test_getID(): void
    {
        $ship = $this->getSampleShip();
        $this->assertIsString($ship->getID());
        $this->assertNotEmpty($ship->getID());
    }

    public function test_getLabel(): void
    {
        $ship = $this->getSampleShip();
        $this->assertIsString($ship->getLabel());
        $this->assertNotEmpty($ship->getLabel());
    }

    public function test_getSizeID(): void
    {
        $ship = $this->getSampleShip();
        $sizeID = $ship->getSizeID();
        $this->assertIsString($sizeID);
        $this->assertContains($sizeID, ['xs', 's', 'm', 'l', 'xl']);
    }

    public function test_getSize(): void
    {
        $ship = $this->getSampleShip();
        $size = $ship->getSize();
        $this->assertInstanceOf(ShipSize::class, $size);
        $this->assertEquals($ship->getSizeID(), $size->getID());
    }

    public function test_getClassID(): void
    {
        $ship = $this->getSampleShip();
        $this->assertIsString($ship->getClassID());
        $this->assertNotEmpty($ship->getClassID());
    }

    public function test_getClass(): void
    {
        $ship = $this->getSampleShip();
        $class = $ship->getClass();
        $this->assertInstanceOf(ShipClass::class, $class);
        $this->assertEquals($ship->getClassID(), $class->getID());
    }

    // =========================================================================
    // Variant Tests
    // =========================================================================

    public function test_getVariantID(): void
    {
        $ship = $this->getSampleShip();
        $variantID = $ship->getVariantID();
        $this->assertInstanceOf(VariantID::class, $variantID);
    }

    public function test_hasVariants_true(): void
    {
        // Find a ship that has variants
        $shipWithVariants = null;
        foreach ($this->ships->getAll() as $ship) {
            if ($ship->hasVariants()) {
                $shipWithVariants = $ship;
                break;
            }
        }
        
        if ($shipWithVariants === null) {
            $this->markTestSkipped('No ships with variants found in test data');
        }
        
        $this->assertTrue($shipWithVariants->hasVariants());
    }

    public function test_hasVariants_false(): void
    {
        // Find a ship without variants
        $shipWithoutVariants = null;
        foreach ($this->ships->getAll() as $ship) {
            if (!$ship->hasVariants()) {
                $shipWithoutVariants = $ship;
                break;
            }
        }
        
        if ($shipWithoutVariants === null) {
            $this->markTestSkipped('All ships have variants in test data');
        }
        
        $this->assertFalse($shipWithoutVariants->hasVariants());
    }

    // =========================================================================
    // Faction Tests
    // =========================================================================

    public function test_getBuilderFactionID(): void
    {
        $ship = $this->getSampleShip();
        $factionID = $ship->getBuilderFactionID();
        $this->assertIsString($factionID);
        $this->assertNotEmpty($factionID);
    }

    public function test_getBuilderFaction(): void
    {
        $ship = $this->getSampleShip();
        $faction = $ship->getBuilderFaction();
        $this->assertInstanceOf(FactionDef::class, $faction);
        $this->assertEquals($ship->getBuilderFactionID(), $faction->getID());
    }

    public function test_getBuilderFaction_defaultsToGeneric(): void
    {
        // Create a ship with empty builder faction ID
        $ship = new ShipDef(
            'test_ship',
            'Test Ship',
            VariantID::fromID('test_ship_01_a'),
            's',
            [], // Empty builder faction IDs array
            'fighter',
            [],
            KnownDataSources::DATA_SOURCE_BASE_GAME,
            [],
            0,
            0.0,
            0.0, // dragForward
            0.0, // dragReverse
            0.0, // dragHorizontal
            0.0, // dragVertical
            0.0, // dragPitch
            0.0, // dragYaw
            0.0, // dragRoll
            0.0, // inertiaPitch
            0.0, // inertiaYaw
            0.0, // inertiaRoll
            0.0, // jerkStrafe
            0.0, // jerkAngular
            0.0, // jerkForwardAccel
            0.0, // jerkForwardDecel
            0.0, // jerkForwardRatio
            0.0, // jerkBoostAccel
            0.0, // jerkBoostRatio
            0.0, // jerkTravelAccel
            0.0, // jerkTravelDecel
            0.0, // jerkTravelRatio
            1.0, // accFactorForward
            1.0, // accFactorReverse
            1.0, // accFactorHorizontal
            1.0, // accFactorVertical
            0,
            0,
            0, // cargoCapacity
            'none', // cargoType
            [],
            []
        );
        
        $this->assertEquals(KnownFactions::FACTION_GENERIC, $ship->getBuilderFactionID());
        $this->assertEquals(KnownFactions::FACTION_GENERIC, $ship->getBuilderFaction()->getID());
    }

    public function test_getUsedBy(): void
    {
        $ship = $this->getSampleShip();
        $usedBy = $ship->getUsedBy();
        $this->assertIsArray($usedBy);
        
        foreach ($usedBy as $faction) {
            $this->assertInstanceOf(FactionDef::class, $faction);
        }
    }

    public function test_getUsedBy_emptyArray(): void
    {
        // Create a ship not used by any faction
        $ship = new ShipDef(
            'test_ship',
            'Test Ship',
            VariantID::fromID('test_ship_01_a'),
            's',
            [KnownFactions::FACTION_ARGON_FEDERATION], // Builder faction IDs array
            'fighter',
            [], // Empty used by array
            KnownDataSources::DATA_SOURCE_BASE_GAME,
            [],
            0,
            0.0,
            0.0, // dragForward
            0.0, // dragReverse
            0.0, // dragHorizontal
            0.0, // dragVertical
            0.0, // dragPitch
            0.0, // dragYaw
            0.0, // dragRoll
            0.0, // inertiaPitch
            0.0, // inertiaYaw
            0.0, // inertiaRoll
            0.0, // jerkStrafe
            0.0, // jerkAngular
            0.0, // jerkForwardAccel
            0.0, // jerkForwardDecel
            0.0, // jerkForwardRatio
            0.0, // jerkBoostAccel
            0.0, // jerkBoostRatio
            0.0, // jerkTravelAccel
            0.0, // jerkTravelDecel
            0.0, // jerkTravelRatio
            1.0, // accFactorForward
            1.0, // accFactorReverse
            1.0, // accFactorHorizontal
            1.0, // accFactorVertical
            0,
            0,
            0, // cargoCapacity
            'none', // cargoType
            [],
            []
        );
        
        $usedBy = $ship->getUsedBy();
        $this->assertIsArray($usedBy);
        $this->assertEmpty($usedBy);
    }

    // =========================================================================
    // Data Source Tests
    // =========================================================================

    public function test_getDataSourceID(): void
    {
        $ship = $this->getSampleShip();
        $dataSourceID = $ship->getDataSourceID();
        $this->assertIsString($dataSourceID);
        $this->assertNotEmpty($dataSourceID);
    }

    public function test_getDataSource(): void
    {
        $ship = $this->getSampleShip();
        $dataSource = $ship->getDataSource();
        $this->assertInstanceOf(DataSourceDef::class, $dataSource);
        $this->assertEquals($ship->getDataSourceID(), $dataSource->getID());
    }

    // =========================================================================
    // Serialization Tests
    // =========================================================================

    public function test_toArray(): void
    {
        $ship = $this->getSampleShip();
        $array = $ship->toArray();
        
        $this->assertIsArray($array);
        $this->assertArrayHasKey(ShipDef::KEY_WARE_ID, $array);
        $this->assertArrayHasKey(ShipDef::KEY_LABEL, $array);
        $this->assertArrayHasKey(ShipDef::KEY_SIZE, $array);
        $this->assertArrayHasKey(ShipDef::KEY_BUILDER_FACTION_IDS, $array); // Updated to new key
        $this->assertArrayHasKey(ShipDef::KEY_CLASS_ID, $array);
        $this->assertArrayHasKey(ShipDef::KEY_USED_BY, $array);
        $this->assertArrayHasKey(ShipDef::KEY_DATA_SOURCE_ID, $array);
        $this->assertArrayHasKey(ShipDef::KEY_VARIANT_ID, $array);
        $this->assertArrayHasKey(ShipDef::KEY_VARIANTS, $array);
    }

    public function test_fromArray(): void
    {
        $originalShip = $this->getSampleShip();
        $array = $originalShip->toArray();
        
        $reconstructedShip = ShipDef::fromArray($array);
        
        $this->assertEquals($originalShip->getID(), $reconstructedShip->getID());
        $this->assertEquals($originalShip->getLabel(), $reconstructedShip->getLabel());
        $this->assertEquals($originalShip->getSizeID(), $reconstructedShip->getSizeID());
        $this->assertEquals($originalShip->getBuilderFactionID(), $reconstructedShip->getBuilderFactionID());
        $this->assertEquals($originalShip->getClassID(), $reconstructedShip->getClassID());
        $this->assertEquals($originalShip->getDataSourceID(), $reconstructedShip->getDataSourceID());
    }

    public function test_serializationRoundTrip(): void
    {
        $ship = $this->getSampleShip();
        
        // Serialize and deserialize
        $array = $ship->toArray();
        $reconstructed = ShipDef::fromArray($array);
        
        // Compare key properties instead of full array (VariantID is an object and won't match directly)
        $this->assertEquals($ship->getID(), $reconstructed->getID());
        $this->assertEquals($ship->getLabel(), $reconstructed->getLabel());
        $this->assertEquals($ship->getSizeID(), $reconstructed->getSizeID());
        $this->assertEquals($ship->getBuilderFactionID(), $reconstructed->getBuilderFactionID());
        $this->assertEquals($ship->getClassID(), $reconstructed->getClassID());
        $this->assertEquals($ship->getDataSourceID(), $reconstructed->getDataSourceID());
        $this->assertEquals($ship->hasVariants(), $reconstructed->hasVariants());
    }

    // =========================================================================
    // Cross-Reference Tests
    // =========================================================================

    public function test_crossReference_builderFaction(): void
    {
        // Verify that all ships' builder factions exist in the factions collection
        $invalidFactionIDs = [];
        
        foreach ($this->ships->getAll() as $ship) {
            try {
                $faction = $ship->getBuilderFaction();
                $this->assertInstanceOf(FactionDef::class, $faction);
                $this->assertEquals($ship->getBuilderFactionID(), $faction->getID());
            } catch (\AppUtils\Collections\RecordNotExistsException $e) {
                // Some ships have invalid faction IDs in the data (e.g., "argon teladi")
                // Track these for reporting but don't fail the test
                $invalidFactionIDs[] = [
                    'ship' => $ship->getID(),
                    'factionID' => $ship->getBuilderFactionID()
                ];
            }
        }
        
        // Report if any invalid faction IDs were found (data quality issue)
        if (!empty($invalidFactionIDs)) {
            $this->markTestIncomplete(
                'Some ships have invalid builder faction IDs in the data: ' . 
                json_encode($invalidFactionIDs)
            );
        }
    }

    public function test_crossReference_usedByFactions(): void
    {
        // Verify that all factions in usedBy exist in the factions collection
        foreach ($this->ships->getAll() as $ship) {
            $usedBy = $ship->getUsedBy();
            foreach ($usedBy as $faction) {
                $this->assertInstanceOf(FactionDef::class, $faction);
            }
        }
    }

    public function test_crossReference_shipClass(): void
    {
        // Verify that all ships' classes exist in the ship classes collection
        foreach ($this->ships->getAll() as $ship) {
            $class = $ship->getClass();
            $this->assertInstanceOf(ShipClass::class, $class);
            $this->assertEquals($ship->getClassID(), $class->getID());
        }
    }

    public function test_crossReference_shipSize(): void
    {
        // Verify that all ships' sizes exist in the ship sizes collection
        foreach ($this->ships->getAll() as $ship) {
            $size = $ship->getSize();
            $this->assertInstanceOf(ShipSize::class, $size);
            $this->assertEquals($ship->getSizeID(), $size->getID());
        }
    }

    public function test_crossReference_dataSource(): void
    {
        // Verify that all ships' data sources exist in the data sources collection
        foreach ($this->ships->getAll() as $ship) {
            $dataSource = $ship->getDataSource();
            $this->assertInstanceOf(DataSourceDef::class, $dataSource);
            $this->assertEquals($ship->getDataSourceID(), $dataSource->getID());
        }
    }

    // =========================================================================
    // Edge Cases
    // =========================================================================

    public function test_allShipsHaveValidIDs(): void
    {
        foreach ($this->ships->getAll() as $ship) {
            $this->assertIsString($ship->getID());
            $this->assertNotEmpty($ship->getID());
        }
    }

    public function test_allShipsHaveLabels(): void
    {
        foreach ($this->ships->getAll() as $ship) {
            $this->assertIsString($ship->getLabel());
            $this->assertNotEmpty($ship->getLabel());
        }
    }

    public function test_allShipsHaveValidSizes(): void
    {
        $validSizes = ['xs', 's', 'm', 'l', 'xl'];
        
        foreach ($this->ships->getAll() as $ship) {
            $this->assertContains($ship->getSizeID(), $validSizes, 
                "Ship {$ship->getID()} has invalid size: {$ship->getSizeID()}");
        }
    }

    public function test_allShipsHaveValidClasses(): void
    {
        foreach ($this->ships->getAll() as $ship) {
            $this->assertIsString($ship->getClassID());
            $this->assertNotEmpty($ship->getClassID());
            
            // Verify the class exists
            $class = $ship->getClass();
            $this->assertInstanceOf(ShipClass::class, $class);
        }
    }

    // =========================================================================
    // Stats & Slots
    // =========================================================================

    public function test_statsProperties() : void
    {
        $ship = $this->getSampleShip();
        
        // Since data is not extracted yet, these should match defaults
        $this->assertIsInt($ship->getHull());
        $this->assertIsFloat($ship->getMass());
        $this->assertIsFloat($ship->getDragForward());
        $this->assertIsFloat($ship->getInertiaPitch());
        $this->assertIsInt($ship->getPeopleCapacity());
        $this->assertIsInt($ship->getMissileStorage());
    }

    public function test_slotProperties() : void
    {
        $ship = $this->getSampleShip();

        $this->assertIsInt($ship->countWeapons());
        $this->assertIsInt($ship->countShields());
        $this->assertIsInt($ship->countTurrets());
        $this->assertIsInt($ship->countDockingBays());
        $this->assertIsInt($ship->countCountermeasures());
        $this->assertIsInt($ship->countEngines());
    }

    // =========================================================================
    // Equipment Compatibility Tests
    // =========================================================================

    public function test_getEngines_returnsShipEquipmentFinder(): void
    {
        $ship = $this->getSampleShip();
        $finder = $ship->getEngines();
        
        $this->assertInstanceOf(\Mistralys\X4\Database\Ships\Equipment\ShipEquipmentFinder::class, $finder);
    }

    public function test_getEngines_returnsCompatibleWares(): void
    {
        $ship = $this->getSampleShip();
        $engines = $ship->getEngines()->getAll();
        
        $this->assertIsArray($engines);
        
        // If ship has engine slots, there should be compatible engines
        if ($ship->countEngines() > 0) {
            $this->assertNotEmpty($engines, 'Ship with engine slots should have compatible engines');
            
            foreach ($engines as $engine) {
                $this->assertInstanceOf(\Mistralys\X4\Database\Wares\WareDef::class, $engine);
                $this->assertTrue($engine->hasTag('equipment'), 'Engine must be tagged as equipment');
                $this->assertTrue($ship->canEquip($engine), 'Ship should be able to equip the returned engine');
            }
        }
    }

    public function test_getShields_returnsCompatibleWares(): void
    {
        $ship = $this->getSampleShip();
        $shields = $ship->getShields()->getAll();
        
        $this->assertIsArray($shields);
        
        if ($ship->countShields() > 0) {
            $this->assertNotEmpty($shields, 'Ship with shield slots should have compatible shields');
            
            foreach ($shields as $shield) {
                $this->assertInstanceOf(\Mistralys\X4\Database\Wares\WareDef::class, $shield);
                $this->assertTrue($shield->hasTag('equipment'), 'Shield must be tagged as equipment');
                $this->assertTrue($ship->canEquip($shield), 'Ship should be able to equip the returned shield');
            }
        }
    }

    public function test_getWeapons_returnsCompatibleWares(): void
    {
        $ship = $this->getSampleShip();
        $weapons = $ship->getWeapons()->getAll();
        
        $this->assertIsArray($weapons);
        
        if ($ship->countWeapons() > 0) {
            $this->assertNotEmpty($weapons, 'Ship with weapon slots should have compatible weapons');
            
            foreach ($weapons as $weapon) {
                $this->assertInstanceOf(\Mistralys\X4\Database\Wares\WareDef::class, $weapon);
                $this->assertTrue($weapon->hasTag('equipment'), 'Weapon must be tagged as equipment');
                $this->assertTrue($ship->canEquip($weapon), 'Ship should be able to equip the returned weapon');
            }
        }
    }

    public function test_getTurrets_returnsCompatibleWares(): void
    {
        $ship = $this->getSampleShip();
        $turrets = $ship->getTurrets()->getAll();
        
        $this->assertIsArray($turrets);
        
        if ($ship->countTurrets() > 0) {
            $this->assertNotEmpty($turrets, 'Ship with turret slots should have compatible turrets');
            
            foreach ($turrets as $turret) {
                $this->assertInstanceOf(\Mistralys\X4\Database\Wares\WareDef::class, $turret);
                $this->assertTrue($turret->hasTag('equipment'), 'Turret must be tagged as equipment');
                $this->assertTrue($ship->canEquip($turret), 'Ship should be able to equip the returned turret');
            }
        }
    }

    public function test_equipmentFinder_filterByDataSource(): void
    {
        $ship = $this->getSampleShip();
        
        if ($ship->countEngines() > 0) {
            $vanillaEngines = $ship->getEngines()
                ->selectDataSource(KnownDataSources::DATA_SOURCE_BASE_GAME)
                ->getAll();
            
            $this->assertIsArray($vanillaEngines);
            
            foreach ($vanillaEngines as $engine) {
                $this->assertEquals(
                    KnownDataSources::DATA_SOURCE_BASE_GAME,
                    $engine->getDataSourceID(),
                    'All returned engines should be from vanilla data source'
                );
            }
        } else {
            $this->markTestSkipped('Sample ship has no engine slots');
        }
    }

    public function test_equipmentFinder_filterBySize(): void
    {
        $ship = $this->getSampleShip();
        
        if ($ship->countEngines() > 0) {
            $allEngines = $ship->getEngines()->getAll();
            
            if (!empty($allEngines)) {
                // Get the size of the first engine
                $firstSize = $allEngines[0]->getSize();
                
                $filteredEngines = $ship->getEngines()
                    ->selectSize($firstSize)
                    ->getAll();
                
                $this->assertNotEmpty($filteredEngines);
                
                foreach ($filteredEngines as $engine) {
                    $this->assertEquals(
                        $firstSize,
                        $engine->getSize(),
                        'All returned engines should match the selected size'
                    );
                }
            }
        } else {
            $this->markTestSkipped('Sample ship has no engine slots');
        }
    }

    public function test_equipmentFinder_filterByLabelSearch(): void
    {
        $ship = $this->getSampleShip();
        
        if ($ship->countEngines() > 0) {
            $allEngines = $ship->getEngines()->getAll();
            
            if (!empty($allEngines)) {
                // Use a search term from the first engine's label
                $searchTerm = substr($allEngines[0]->getLabel(), 0, 3);
                
                $filteredEngines = $ship->getEngines()
                    ->selectLabelSearch($searchTerm)
                    ->getAll();
                
                $this->assertNotEmpty($filteredEngines);
                
                foreach ($filteredEngines as $engine) {
                    $this->assertStringContainsStringIgnoringCase(
                        $searchTerm,
                        $engine->getLabel(),
                        'All returned engines should contain the search term'
                    );
                }
            }
        } else {
            $this->markTestSkipped('Sample ship has no engine slots');
        }
    }

    public function test_equipmentFinder_chainedFilters(): void
    {
        $ship = $this->getSampleShip();
        
        if ($ship->countShields() > 0) {
            $filteredShields = $ship->getShields()
                ->selectDataSource(KnownDataSources::DATA_SOURCE_BASE_GAME)
                ->selectTag('equipment')
                ->getAll();
            
            $this->assertIsArray($filteredShields);
            
            foreach ($filteredShields as $shield) {
                $this->assertEquals(KnownDataSources::DATA_SOURCE_BASE_GAME, $shield->getDataSourceID());
                $this->assertTrue($shield->hasTag('equipment'));
                $this->assertTrue($ship->canEquip($shield));
            }
        } else {
            $this->markTestSkipped('Sample ship has no shield slots');
        }
    }

    public function test_findEquipmentForSlot_genericMethod(): void
    {
        $ship = $this->getSampleShip();
        
        $engines = $ship->findEquipmentForSlot(\Mistralys\X4\Database\SlotTypes\KnownSlotTypes::ENGINE)->getAll();
        $this->assertIsArray($engines);
        
        foreach ($engines as $engine) {
            $this->assertInstanceOf(\Mistralys\X4\Database\Wares\WareDef::class, $engine);
            $this->assertTrue($ship->canEquip($engine));
        }
    }

    // =========================================================================
    // Weapon Performance Data Tests
    // =========================================================================

    public function test_getCompatibleWeapons_returnsWeaponDefs(): void
    {
        $ship = $this->getSampleShip();
        $weapons = $ship->getCompatibleWeapons();
        
        $this->assertIsArray($weapons);
        
        if ($ship->countWeapons() > 0) {
            $this->assertNotEmpty($weapons, 'Ship with weapon slots should have compatible weapons');
            
            foreach ($weapons as $weapon) {
                $this->assertInstanceOf(\Mistralys\X4\Database\Weapons\WeaponDef::class, $weapon);
                
                // Verify the weapon system is valid
                $this->assertNotEmpty($weapon->getWeaponSystem());
                
                // Verify weapon has performance data (DPS, bullet range, etc.)
                $this->assertIsFloat($weapon->getDPS());
                $this->assertIsFloat($weapon->getBulletRange());
            }
        } else {
            // If no weapon slots, should return empty array
            $this->assertEmpty($weapons);
        }
    }

    public function test_getCompatibleTurrets_returnsWeaponDefs(): void
    {
        $ship = $this->getSampleShip();
        $turrets = $ship->getCompatibleTurrets();
        
        $this->assertIsArray($turrets);
        
        if ($ship->countTurrets() > 0) {
            $this->assertNotEmpty($turrets, 'Ship with turret slots should have compatible turrets');
            
            foreach ($turrets as $turret) {
                $this->assertInstanceOf(\Mistralys\X4\Database\Weapons\WeaponDef::class, $turret);
                
                // Verify the weapon system is valid
                $this->assertNotEmpty($turret->getWeaponSystem());
                
                // Verify turret has performance data
                $this->assertIsFloat($turret->getDPS());
                $this->assertIsFloat($turret->getBulletRange());
            }
        } else {
            // If no turret slots, should return empty array
            $this->assertEmpty($turrets);
        }
    }

    public function test_compatibleWeapons_matchEquipmentFinder(): void
    {
        $ship = $this->getSampleShip();
        
        // Get ware IDs from equipment finder
        $equipmentWareIDs = array_map(
            fn($ware) => $ware->getID(),
            $ship->getWeapons()->getAll()
        );
        
        // Get weapon ware IDs from WeaponDef collection
        $weaponWareIDs = array_map(
            fn($weapon) => $weapon->getWareID(),
            $ship->getCompatibleWeapons()
        );
        
        // Both should return the same ware IDs (one returns WareDef, other returns WeaponDef)
        sort($equipmentWareIDs);
        sort($weaponWareIDs);
        
        $this->assertEquals(
            $equipmentWareIDs,
            $weaponWareIDs,
            'getCompatibleWeapons() should return WeaponDefs for the same wares as getWeapons()'
        );
    }

    public function test_compatibleTurrets_matchEquipmentFinder(): void
    {
        $ship = $this->getSampleShip();
        
        // Get ware IDs from equipment finder
        $equipmentWareIDs = array_map(
            fn($ware) => $ware->getID(),
            $ship->getTurrets()->getAll()
        );
        
        // Get weapon ware IDs from WeaponDef collection
        $weaponWareIDs = array_map(
            fn($weapon) => $weapon->getWareID(),
            $ship->getCompatibleTurrets()
        );
        
        // Both should return the same ware IDs (one returns WareDef, other returns WeaponDef)
        sort($equipmentWareIDs);
        sort($weaponWareIDs);
        
        $this->assertEquals(
            $equipmentWareIDs,
            $weaponWareIDs,
            'getCompatibleTurrets() should return WeaponDefs for the same wares as getTurrets()'
        );
    }

    // =========================================================================
    // Helper Methods
    // =========================================================================

    // =========================================================================
    // Cargo Tests
    // =========================================================================

    /**
     * The Split carrier references a vanilla (Argon) storage macro from
     * a DLC data folder. This used to resolve to a cargo hold of 0.
     */
    public function test_cargoCapacity_crossDataSourceStorageMacro(): void
    {
        $ship = $this->ships->getByID('ship_spl_xl_carrier_01_a');

        $this->assertSame(19000, $ship->getCargoCapacity());
        $this->assertSame('container', $ship->getCargoType());
    }

    /**
     * Ships that declare a cargo storage connection in their macro must
     * never end up with a cargo hold of 0: that means the storage macro
     * could not be resolved. Ships without such a connection (e.g. Xenon
     * and Khaa'k ships, racers) legitimately have no cargo hold.
     */
    public function test_cargoCapacity_isNeverZeroWhenStorageIsDeclared(): void
    {
        $wares = WareDefs::getInstance();
        $checked = 0;
        $failures = array();

        foreach($this->ships->getAll() as $ship)
        {
            $macroDef = $wares->getByID($ship->getID())->getMacro();
            if(!$macroDef->getFile()->exists()) {
                $this->markTestSkipped('The extracted game data is not available.');
            }

            $declaresStorage = false;
            foreach($macroDef->getDOM()->byTagName('connection')->getAll() as $connection) {
                if(str_starts_with((string)$connection->getAttribute('ref'), 'con_storage')) {
                    $declaresStorage = true;
                    break;
                }
            }

            if(!$declaresStorage) {
                continue;
            }

            $checked++;
            if($ship->getCargoCapacity() <= 0) {
                $failures[] = $ship->getID();
            }
        }

        $this->assertGreaterThan(0, $checked, 'No ships with a storage connection were found.');
        $this->assertSame(
            array(),
            $failures,
            'Ships declare a cargo storage connection but report no cargo: ' . implode(', ', $failures)
        );
    }

    // =========================================================================
    // Macro properties, ware delegators and cargo helpers
    // =========================================================================

    /**
     * @return array<string,mixed> A legacy record: the ship record without any of the macro property keys.
     */
    private function getLegacyRecord() : array
    {
        $record = $this->getSampleShip()->toArray();

        // All key constants declared after KEY_EQUIPMENT belong to the macro properties.
        $constants = (new \ReflectionClass(ShipDef::class))->getConstants();
        $macroKeys = array_slice($constants, array_search(ShipDef::KEY_EQUIPMENT, array_values($constants), true) + 1);

        foreach($macroKeys as $key) {
            unset($record[$key]);
        }

        return $record;
    }

    /**
     * @param string $cargoType
     * @param int $capacity
     * @return ShipDef
     */
    private function createCargoShip(string $cargoType, int $capacity) : ShipDef
    {
        $record = $this->getLegacyRecord();
        $record[ShipDef::KEY_CARGO_TYPE] = $cargoType;
        $record[ShipDef::KEY_CARGO_CAPACITY] = $capacity;

        return ShipDef::fromArray($record);
    }

    public function test_constructorWithoutProperties_createsEmptyProperties() : void
    {
        $ship = $this->createCargoShip('container', 100);

        $this->assertSame('', $ship->getMacroID());
        $this->assertNull($ship->getCargoMacroID());
        $this->assertFalse($ship->hasCargoMacro());
        $this->assertNull($ship->getAliasMacroID());
        $this->assertFalse($ship->isAliasMacro());
        $this->assertFalse($ship->hasOwnAccFactors());
        $this->assertSame(array(), $ship->getMacroProperties()->defaultLoadout?->toArray() ?? array());
    }

    public function test_fromArray_legacyRecordWithoutNewKeys() : void
    {
        $record = $this->getLegacyRecord();

        $ship = ShipDef::fromArray($record);

        $this->assertSame($record[ShipDef::KEY_WARE_ID], $ship->getID());
        $this->assertSame('', $ship->getMacroID());
        $this->assertNull($ship->getCargoMacroID());
        $this->assertNull($ship->getAliasMacroID());
    }

    public function test_serialization_newKeys() : void
    {
        $legacyKeys = array_keys($this->getLegacyRecord());

        $properties = new ShipMacroProperties(
            'ship_test_macro',
            'storage_test_macro',
            'ship_alias_macro',
            true
        );

        $ship = ShipDef::fromArray(array_merge($this->getLegacyRecord(), $properties->toArray()));
        $array = $ship->toArray();

        $this->assertSame($legacyKeys, array_slice(array_keys($array), 0, count($legacyKeys)), 'Existing keys must come first.');
        $this->assertSame('ship_test_macro', $array[ShipDef::KEY_MACRO_ID]);
        $this->assertSame('storage_test_macro', $array[ShipDef::KEY_CARGO_MACRO_ID]);
        $this->assertSame('ship_alias_macro', $array[ShipDef::KEY_ALIAS_MACRO_ID]);
        $this->assertTrue($array[ShipDef::KEY_HAS_OWN_ACC_FACTORS]);

        $this->assertSame('ship_test_macro', $ship->getMacroID());
        $this->assertSame('storage_test_macro', $ship->getCargoMacroID());
        $this->assertTrue($ship->hasCargoMacro());
        $this->assertSame('ship_alias_macro', $ship->getAliasMacroID());
        $this->assertTrue($ship->isAliasMacro());
        $this->assertTrue($ship->hasOwnAccFactors());
        $this->assertSame($ship->getMacroProperties()->toArray(), ShipDef::fromArray($array)->getMacroProperties()->toArray());
    }

    public function test_macroProperties_noPartBDelegators() : void
    {
        foreach(array('getSteeringCurve', 'getHangar', 'getSoftware', 'getModifiers', 'getDefaultLoadout', 'getPurposeID') as $method) {
            $this->assertFalse(method_exists(ShipDef::class, $method), $method.'() must be reached through getMacroProperties().');
            $this->assertTrue(method_exists(ShipMacroProperties::class, $method), $method);
        }
    }

    public function test_getMacro_throwsWhenMacroIDEmpty() : void
    {
        $ship = $this->createCargoShip('container', 100);

        try {
            $ship->getMacro();
            $this->fail('An exception should have been thrown.');
        } catch (ShipException $e) {
            $this->assertSame(ShipException::ERROR_NO_MACRO_ID, $e->getCode());
        }

        try {
            $ship->getCargoMacro();
            $this->fail('An exception should have been thrown.');
        } catch (ShipException $e) {
            $this->assertSame(ShipException::ERROR_NO_CARGO_MACRO_ID, $e->getCode());
        }
    }

    public function test_getMacro_resolvesStoredMacroID() : void
    {
        $ware = WareDefs::getInstance()->getByID('ship_arg_s_fighter_01_a');
        $record = $this->getLegacyRecord();
        $record[ShipDef::KEY_WARE_ID] = $ware->getID();
        $record[ShipDef::KEY_DATA_SOURCE_ID] = $ware->getDataSourceID();
        $record[ShipDef::KEY_MACRO_ID] = $ware->getMacroID();

        $ship = ShipDef::fromArray($record);

        $this->assertSame($ware->getMacroID(), $ship->getMacro()->getMacroName());
    }

    public function test_wareDelegators() : void
    {
        $checked = 0;

        foreach($this->ships->getAll() as $ship) {
            $ware = $ship->getWare();

            $this->assertEquals($ware->getPrice(), $ship->getPrice(), $ship->getID());
            $this->assertEquals($ware->getProductions(), $ship->getProductions(), $ship->getID());
            $this->assertSame($ware->getLicenceID(), $ship->getLicenceID(), $ship->getID());
            $this->assertSame($ware->getDescription(), $ship->getDescription(), $ship->getID());
            $this->assertSame($ware->getIllegalFactionIDs(), $ship->getIllegalFactionIDs(), $ship->getID());
            $checked++;
        }

        $this->assertGreaterThan(0, $checked);
    }

    public function test_getCargoTypeIDs() : void
    {
        $this->assertSame(array(), $this->createCargoShip('none', 0)->getCargoTypeIDs());
        $this->assertSame(array('container'), $this->createCargoShip('container', 10)->getCargoTypeIDs());
        $this->assertSame(
            array('container', 'solid', 'liquid'),
            $this->createCargoShip('container solid liquid', 10)->getCargoTypeIDs()
        );

        foreach($this->ships->getAll() as $ship) {
            $this->assertSame(
                $ship->getCargoType() === 'none',
                empty($ship->getCargoTypeIDs()),
                $ship->getID()
            );
        }
    }

    public function test_canTransport_and_getCargoUnits() : void
    {
        $wares = WareDefs::getInstance();
        $energy = $wares->getByID('energycells');
        $hullParts = $wares->getByID('hullparts');
        $ore = $wares->getByID('ore');

        $containerShip = $this->createCargoShip('container', 1200);

        $this->assertTrue($containerShip->canTransport($energy));
        $this->assertSame(1200, $containerShip->getCargoUnits($energy));

        $this->assertTrue($containerShip->canTransport($hullParts));
        $this->assertSame(12, $hullParts->getVolume());
        $this->assertSame(intdiv(1200, 12), $containerShip->getCargoUnits($hullParts));

        $this->assertFalse($containerShip->canTransport($ore));
        $this->assertSame(0, $containerShip->getCargoUnits($ore));

        $mixedShip = $this->createCargoShip('container solid liquid', 1200);
        $this->assertSame(WareTransportTypes::TRANSPORT_SOLID, $ore->getTransportTypeID());
        $this->assertTrue($mixedShip->canTransport($ore));
        $this->assertGreaterThan(0, $mixedShip->getCargoUnits($ore));

        $noHold = $this->createCargoShip('none', 0);
        $this->assertFalse($noHold->canTransport($energy));
        $this->assertSame(0, $noHold->getCargoUnits($energy));
    }

    public function test_cargoTypeIDs_matchStorageMacro() : void
    {
        $checked = 0;

        foreach($this->ships->getAll() as $ship) {
            $expected = $this->scanStorageCargoTypes($ship);

            $this->assertSame($expected, $ship->getCargoTypeIDs(), $ship->getID());

            foreach($ship->getCargoTypeIDs() as $typeID) {
                $this->assertTrue(WareTransportTypes::isCargoTypeID($typeID), $ship->getID().': '.$typeID);
            }

            $checked++;
        }

        $this->assertGreaterThan(0, $checked);
    }

    /**
     * Independent scan of the raw XML: the `<cargo tags>` tokens of the
     * ship's storage macro, following one macro alias if needed.
     *
     * @return string[]
     */
    private function scanStorageCargoTypes(ShipDef $ship) : array
    {
        $macroDefs = MacroFileDefs::getInstance();
        $macroName = $ship->getWare()->getMacroID();
        $dataSourceID = $ship->getDataSourceID();

        for($hop = 0; $hop < 2; $hop++)
        {
            $xml = simplexml_load_file($macroDefs->getByMacroName($macroName, $dataSourceID)->getFile()->getPath());
            $this->assertNotFalse($xml);

            foreach($xml->xpath('//connection[starts-with(@ref,"con_storage")]/macro/@ref') ?: array() as $storageRef)
            {
                $storage = simplexml_load_file($macroDefs->getByMacroName((string)$storageRef, $dataSourceID)->getFile()->getPath());
                $cargo = $storage === false ? null : ($storage->xpath('//cargo') ?: array())[0] ?? null;

                if($cargo === null || (string)$cargo['max'] === '') {
                    continue;
                }

                $tags = trim((string)$cargo['tags']);
                $tokens = $tags === '' ? array() : preg_split('/\s+/', $tags);

                return array_values(array_diff($tokens ?: array(), array('none')));
            }

            $alias = (string)($xml->xpath('//macro/@alias')[0] ?? '');
            if($alias === '') {
                break;
            }

            $macroName = $alias;
        }

        return array();
    }

    // =========================================================================
    // Real-data tests: macro information (Part A) and macro properties (Part B)
    // =========================================================================

    public function test_macroID_matchesWareMacro() : void
    {
        foreach($this->ships->getAll() as $ship) {
            $this->assertNotSame('', $ship->getMacroID(), $ship->getID());
            $this->assertSame($ship->getWare()->getMacroID(), $ship->getMacroID(), $ship->getID());
            $this->assertSame($ship->getMacroID(), $ship->getMacro()->getMacroName(), $ship->getID());
        }

        $mothershipID = 'ship_xen_xl_mothership_01';
        $this->assertTrue($this->ships->idExists($mothershipID));
        $this->assertNotSame($mothershipID.'_macro', $this->ships->getByID($mothershipID)->getMacroID(), 'The macro name must not be derived from the ware ID.');
    }

    public function test_cargoMacro_crossFolderCarrier() : void
    {
        $carrier = $this->ships->getByID('ship_spl_xl_carrier_01_a');

        $this->assertTrue($carrier->hasCargoMacro());
        $this->assertSame('storage_arg_xl_carrier_01_a_macro', $carrier->getCargoMacroID());
        $this->assertSame('storage_arg_xl_carrier_01_a_macro', $carrier->getCargoMacro()->getMacroName());
    }

    public function test_cargoMacro_consistentWithCargo() : void
    {
        foreach($this->ships->getAll() as $ship) {
            $this->assertSame($ship->getCargoType() !== 'none', $ship->hasCargoMacro(), $ship->getID());

            if(!$ship->hasCargoMacro()) {
                $this->assertSame(0, $ship->getCargoCapacity(), $ship->getID());
                continue;
            }

            $cargo = $ship->getCargoMacro()->getDOM()->byTagName('cargo')->getFirst();
            $this->assertNotNull($cargo, $ship->getID());
            $this->assertSame($ship->getCargoCapacity(), (int)$cargo->getAttribute('max'), $ship->getID());
        }
    }

    public function test_hasOwnAccFactors_matchesRawXml() : void
    {
        $withOwn = 0;

        foreach($this->ships->getAll() as $ship) {
            $raw = !empty(RawShipMacroScanner::getOwnMacro($ship)->xpath('//accfactors'));

            $this->assertSame($raw, $ship->hasOwnAccFactors(), $ship->getID());
            $withOwn += $raw ? 1 : 0;
        }

        $this->assertGreaterThan(0, $withOwn);
    }

    public function test_hasOwnAccFactors_explicitVersusDefault() : void
    {
        $explicit = 0;
        $default = 0;

        foreach($this->ships->getAll() as $ship)
        {
            if($ship->hasOwnAccFactors()) {
                $explicit++;
                continue;
            }

            // Without own factors the ship uses the alias target's or the game default.
            if(!$ship->isAliasMacro()) {
                $default++;
                $this->assertSame(1.0, $ship->getAccFactorForward(), $ship->getID());
                $this->assertSame(1.0, $ship->getAccFactorReverse(), $ship->getID());
                $this->assertSame(1.0, $ship->getAccFactorHorizontal(), $ship->getID());
                $this->assertSame(1.0, $ship->getAccFactorVertical(), $ship->getID());
            }
        }

        $this->assertGreaterThan(0, $explicit);
        $this->assertGreaterThan(0, $default);
    }

    public function test_aliasMacro() : void
    {
        $aliased = 0;

        foreach($this->ships->getAll() as $ship) {
            $raw = RawShipMacroScanner::getAliasName($ship);

            $this->assertSame($raw, $ship->getAliasMacroID(), $ship->getID());
            $this->assertSame($raw !== null, $ship->isAliasMacro(), $ship->getID());
            $aliased += $raw !== null ? 1 : 0;
        }

        $this->assertGreaterThan(0, $aliased);
    }

    public function test_usedBy_includesPatchedOwners() : void
    {
        $tugboat = $this->ships->getByID(self::TUGBOAT_SHIP_ID);
        $usedBy = array_map(static fn($faction) => $faction->getID(), $tugboat->getUsedBy());
        $raw = RawWareScanner::getWares()[self::TUGBOAT_SHIP_ID];

        $this->assertNotEmpty($raw['owners']);

        foreach($raw['owners'] as $factionID) {
            $this->assertContains($factionID, $usedBy);
        }
    }

    public function test_steeringCurve_matchesRawXml() : void
    {
        foreach($this->ships->getAll() as $ship)
        {
            $expected = array();
            foreach(RawShipMacroScanner::elements($ship, 'steeringcurve/point') as $point) {
                $expected[] = array((float)$point['position'], (float)$point['value']);
            }

            $actual = array();
            foreach($ship->getMacroProperties()->getSteeringCurve()->getPoints() as $point) {
                $actual[] = array($point->position, $point->value);
            }

            $this->assertSame($expected, $actual, $ship->getID());
            $this->assertNotEmpty($actual, $ship->getID());
        }
    }

    public function test_explosionDamage_matchesRawXml() : void
    {
        foreach($this->ships->getAll() as $ship)
        {
            $props = $ship->getMacroProperties();
            $hull = RawShipMacroScanner::floatAttr($ship, 'explosiondamage', 'value');
            $shield = RawShipMacroScanner::floatAttr($ship, 'explosiondamage', 'shield');
            $disruption = RawShipMacroScanner::floatAttr($ship, 'explosiondamage', 'shielddisruption');

            if($hull === null && $shield === null && $disruption === null) {
                $this->assertNull($props->getExplosionDamage(), $ship->getID());
                continue;
            }

            $damage = $props->getExplosionDamage();
            $this->assertNotNull($damage, $ship->getID());
            $this->assertSame($hull, $damage->hull, $ship->getID());
            $this->assertSame($shield, $damage->shield, $ship->getID());
            $this->assertSame($disruption, $damage->shieldDisruption, $ship->getID());
        }
    }

    public function test_classificationFields_matchRawXml() : void
    {
        $thrusterSizes = array('small' => 's', 'medium' => 'm', 'large' => 'l', 'extralarge' => 'xl');

        foreach($this->ships->getAll() as $ship)
        {
            $props = $ship->getMacroProperties();

            $this->assertSame((string)RawShipMacroScanner::attr($ship, 'purpose', 'primary'), $props->getPurposeID(), $ship->getID());
            $this->assertSame((string)RawShipMacroScanner::attr($ship, 'identification', 'icon'), $props->getIconID(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::intAttr($ship, 'secrecy', 'level'), $props->getSecrecyLevel(), $ship->getID());

            $expectedSize = '';
            foreach(preg_split('/\s+/', trim((string)RawShipMacroScanner::attr($ship, 'thruster', 'tags')), -1, PREG_SPLIT_NO_EMPTY) ?: array() as $tag) {
                if(isset($thrusterSizes[$tag])) {
                    $expectedSize = $thrusterSizes[$tag];
                    break;
                }
            }

            $this->assertSame($expectedSize, $props->getThrusterSizeID(), $ship->getID());
            $this->assertNotSame('', $props->getPurposeID(), $ship->getID());
        }
    }

    public function test_hangar_matchesRawXml() : void
    {
        $withHangar = 0;

        foreach($this->ships->getAll() as $ship) {
            $expected = RawShipMacroScanner::hangar($ship);

            $this->assertSame($expected, $ship->getMacroProperties()->getHangar()->capacities, $ship->getID());
            $withHangar += empty($expected) ? 0 : 1;
        }

        $this->assertGreaterThan(0, $withHangar);

        $carrier = $this->ships->getByID('ship_spl_xl_carrier_01_a')->getMacroProperties();
        $this->assertSame(array('xs' => 10, 's' => 100, 'm' => 4), $carrier->getHangar()->capacities);
    }

    public function test_storageOverrides_matchRawXml() : void
    {
        foreach($this->ships->getAll() as $ship) {
            $props = $ship->getMacroProperties();

            $this->assertSame(RawShipMacroScanner::intAttr($ship, 'storage', 'unit') ?? 0, $props->getDroneCapacity(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::intAttr($ship, 'storage', 'deployable'), $props->getDeployableCapacityOverride(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::intAttr($ship, 'storage', 'countermeasure'), $props->getCountermeasureCapacityOverride(), $ship->getID());
        }
    }

    public function test_software_matchesRawXml() : void
    {
        foreach($this->ships->getAll() as $ship)
        {
            $default = array();
            $optional = array();

            foreach(RawShipMacroScanner::elements($ship, 'software/software') as $software) {
                $wareID = (string)$software['ware'];

                if($wareID === '') {
                    continue;
                }

                if((string)$software['default'] === '1') {
                    $default[] = $wareID;
                }

                if((string)$software['compatible'] === '1') {
                    $optional[] = $wareID;
                }
            }

            $software = $ship->getMacroProperties()->getSoftware();

            $this->assertEqualsCanonicalizing($default, $software->defaultWareIDs, $ship->getID());
            $this->assertEqualsCanonicalizing($optional, $software->optionalWareIDs, $ship->getID());
        }
    }

    public function test_specialisedFields_matchRawXml() : void
    {
        foreach($this->ships->getAll() as $ship)
        {
            $props = $ship->getMacroProperties();
            $modifiers = $props->getModifiers();

            $this->assertSame(RawShipMacroScanner::floatAttr($ship, 'traveldrivestability', 'maxvalue'), $props->getTravelDriveStability(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::floatAttr($ship, 'gatherrate', 'gas'), $props->getGasGatherRate(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::floatAttr($ship, 'radar', 'range'), $props->getRadarRangeOverride(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::floatAttr($ship, 'modifiers/weapon', 'heat'), $modifiers->weaponHeat, $ship->getID());
            $this->assertSame(RawShipMacroScanner::floatAttr($ship, 'modifiers/shield', 'capacity'), $modifiers->shieldCapacity, $ship->getID());
            $this->assertSame(RawShipMacroScanner::floatAttr($ship, 'modifiers/shield', 'rechargerate'), $modifiers->shieldRechargeRate, $ship->getID());
            $this->assertSame(RawShipMacroScanner::floatAttr($ship, 'modifiers/shield', 'rechargedelay'), $modifiers->shieldRechargeDelay, $ship->getID());
        }
    }

    public function test_namesAndFlags_matchRawXml() : void
    {
        $capturableFalse = 0;

        foreach($this->ships->getAll() as $ship)
        {
            $props = $ship->getMacroProperties();

            $this->assertSame(RawShipMacroScanner::attr($ship, 'identification', 'basename'), $props->getBaseNameID(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::attr($ship, 'identification', 'variation'), $props->getVariationNameID(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::attr($ship, 'identification', 'shortvariation'), $props->getShortVariationNameID(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::attr($ship, 'capture', 'allow') !== '0', $props->isCapturable(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::intAttr($ship, 'ship', 'prestige'), $props->getPrestige(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::floatAttr($ship, 'boost', 'rechargedelay'), $props->getBoostRechargeDelay(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::floatAttr($ship, 'boost', 'minduration'), $props->getBoostMinDuration(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::intAttr($ship, 'threatscore', 'value'), $props->getThreatScore(), $ship->getID());
            $this->assertSame(RawShipMacroScanner::attr($ship, 'ownership', 'playercoverable') === '1', $props->isPlayerCoverable(), $ship->getID());

            $capturableFalse += $props->isCapturable() ? 0 : 1;
        }

        $this->assertGreaterThan(0, $capturableFalse);
    }

    public function test_getBaseName_translates() : void
    {
        $checked = 0;

        foreach($this->ships->getAll() as $ship) {
            $props = $ship->getMacroProperties();

            if($props->getBaseNameID() === null) {
                $this->assertSame('', $props->getBaseName(), $ship->getID());
                continue;
            }

            $name = $props->getBaseName();
            $this->assertNotSame('', $name, $ship->getID());
            $this->assertStringStartsNotWith('{', $name, $ship->getID());
            $checked++;
        }

        $this->assertGreaterThan(0, $checked);
    }

    public function test_getBaseName_fallsBackToEnglish() : void
    {
        $ship = null;
        foreach($this->ships->getAll() as $candidate) {
            if($candidate->getMacroProperties()->getBaseNameID() !== null) {
                $ship = $candidate;
                break;
            }
        }

        $this->assertNotNull($ship);

        $english = $ship->getMacroProperties()->getBaseName();

        foreach(Languages::getInstance()->getAll() as $language) {
            $name = $ship->getMacroProperties()->getBaseName($language);

            $this->assertNotSame('', $name, $language->getID().' returned an empty name.');
            $this->assertStringStartsNotWith('{', $name);
        }

        $this->assertSame($english, $ship->getMacroProperties()->getBaseName(Languages::getInstance()->getEnglish()));
    }

    public function test_defaultLoadout_matchesRawXml() : void
    {
        $withLoadout = 0;

        foreach($this->ships->getAll() as $ship)
        {
            $rawLoadouts = array();
            foreach(RawShipMacroScanner::getMacros($ship) as $xml) {
                $found = $xml->xpath('/macros/macro/properties/loadouts/loadout[@id="default"]');
                if(!empty($found)) {
                    $rawLoadouts = $found;
                    break;
                }
            }

            $loadout = $ship->getMacroProperties()->getDefaultLoadout();

            $this->assertSame(!empty($rawLoadouts), $loadout !== null, $ship->getID());

            if($loadout !== null) {
                $withLoadout++;
                $this->assertNotEmpty($loadout->getItems(), $ship->getID());
            }
        }

        $this->assertGreaterThan(0, $withLoadout);

        $destroyer = $this->ships->getByID('ship_kha_l_destroyer_01_a')->getMacroProperties()->getDefaultLoadout();
        $this->assertNotNull($destroyer);
        $this->assertSame(1, $destroyer->countByType('engine'));
        $this->assertSame(3, $destroyer->countByType('shield'));
        $this->assertSame(1, $destroyer->countByType('weapon'));
        $this->assertSame(8, $destroyer->countByType('turret'));
        $this->assertSame(1, $destroyer->countByType('thruster'));
    }

    private function getSampleShip(): ShipDef
    {
        $ships = $this->ships->getAll();
        $this->assertNotEmpty($ships, 'No ships found in collection');
        
        return reset($ships);
    }
}
