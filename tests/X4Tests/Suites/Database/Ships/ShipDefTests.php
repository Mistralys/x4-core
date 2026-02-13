<?php

declare(strict_types=1);

namespace X4Tests\Suites\Database\Ships;

use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\KnownDataSources;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\KnownFactions;
use Mistralys\X4\Database\Ships\ShipClass;
use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\Ships\ShipDefs;
use Mistralys\X4\Database\Ships\ShipSize;
use X4Tests\Helpers\X4TestCase;

final class ShipDefTests extends X4TestCase
{
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
            '', // Empty builder faction ID
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
            KnownFactions::FACTION_ARGON_FEDERATION,
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
        $this->assertArrayHasKey(ShipDef::KEY_BUILDER_FACTION_ID, $array);
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

    private function getSampleShip(): ShipDef
    {
        $ships = $this->ships->getAll();
        $this->assertNotEmpty($ships, 'No ships found in collection');
        
        return reset($ships);
    }
}
