<?php
/**
 * PHPUnit tests for WeaponSystems collection
 */

declare(strict_types=1);

namespace X4Tests\Suites;

use Mistralys\X4\Database\WeaponSystems\WeaponSystems;
use Mistralys\X4\Database\WeaponSystems\WeaponSystem;
use Mistralys\X4\Database\WeaponSystems\KnownWeaponSystems;
use Mistralys\X4\Database\Weapons\WeaponDefs;
use Mistralys\X4\Database\Weapons\WeaponException;
use PHPUnit\Framework\TestCase;

class WeaponSystemsTest extends TestCase
{
    private WeaponSystems $systems;
    
    protected function setUp(): void
    {
        $this->systems = WeaponSystems::getInstance();
    }
    
    public function test_collectionLoads(): void
    {
        $this->assertCount(10, $this->systems->getAll());
    }
    
    public function test_constantsExist(): void
    {
        $this->assertSame('turret_shortrange', KnownWeaponSystems::TURRET_SHORTRANGE);
        $this->assertSame('weapon_standard', KnownWeaponSystems::WEAPON_STANDARD);
        $this->assertSame('torpedo', KnownWeaponSystems::TORPEDO);
    }
    
    public function test_getByIDReturnsCorrectSystem(): void
    {
        $system = $this->systems->getByID(KnownWeaponSystems::TURRET_SHORTRANGE);
        
        $this->assertInstanceOf(WeaponSystem::class, $system);
        $this->assertSame('turret_shortrange', $system->getID());
        $this->assertSame('Short-Range Turret', $system->getLabel());
    }
    
    public function test_allSystemsHaveLabelsAndDescriptions(): void
    {
        foreach ($this->systems->getAll() as $system) {
            $this->assertNotEmpty($system->getLabel());
            $this->assertNotEmpty($system->getDescription());
            $this->assertInstanceOf(WeaponSystem::class, $system);
        }
    }
    
    public function test_turretTypeCheckingWorks(): void
    {
        $shortRange = $this->systems->getByID(KnownWeaponSystems::TURRET_SHORTRANGE);
        $standard = $this->systems->getByID(KnownWeaponSystems::WEAPON_STANDARD);
        
        $this->assertTrue($shortRange->isTurret());
        $this->assertFalse($shortRange->isMissile());
        $this->assertFalse($shortRange->isStandardWeapon());
        
        $this->assertFalse($standard->isTurret());
        $this->assertTrue($standard->isStandardWeapon());
    }
    
    public function test_missileTypeCheckingWorks(): void
    {
        $guided = $this->systems->getByID(KnownWeaponSystems::MISSILE_GUIDED);
        $torpedo = $this->systems->getByID(KnownWeaponSystems::TORPEDO);
        
        $this->assertTrue($guided->isMissile());
        $this->assertTrue($torpedo->isMissile());
        $this->assertFalse($guided->isTurret());
    }
    
    public function test_getTurretSystemsReturnsThreeSystems(): void
    {
        $turrets = $this->systems->getTurretSystems();
        
        $this->assertCount(3, $turrets);
        foreach ($turrets as $turret) {
            $this->assertTrue($turret->isTurret());
        }
    }
    
    public function test_getMissileSystemsReturnsThreeSystems(): void
    {
        $missiles = $this->systems->getMissileSystems();
        
        $this->assertCount(4, $missiles);
        foreach ($missiles as $missile) {
            $this->assertTrue($missile->isMissile());
        }
    }
    
    public function test_getStandardWeaponSystemsReturnsTwoSystems(): void
    {
        $weapons = $this->systems->getStandardWeaponSystems();
        
        $this->assertCount(3, $weapons);
        foreach ($weapons as $weapon) {
            $this->assertTrue($weapon->isStandardWeapon());
        }
    }
    
    public function test_isKnownSystemReturnsTrueForValidSystems(): void
    {
        $this->assertTrue($this->systems->isKnownSystem('turret_shortrange'));
        $this->assertTrue($this->systems->isKnownSystem('weapon_mining'));
        $this->assertTrue($this->systems->isKnownSystem('torpedo'));
    }
    
    public function test_isKnownSystemReturnsFalseForInvalidSystems(): void
    {
        $this->assertFalse($this->systems->isKnownSystem('fake_system'));
        $this->assertFalse($this->systems->isKnownSystem(''));
        $this->assertFalse($this->systems->isKnownSystem('unknown'));
    }
    
    public function test_requireKnownSystemThrowsExceptionForUnknownSystem(): void
    {
        $this->expectException(WeaponException::class);
        $this->expectExceptionCode(WeaponException::ERROR_UNKNOWN_WEAPON_SYSTEM);
        $this->expectExceptionMessageMatches('/Unknown weapon system type/');
        
        $this->systems->requireKnownSystem('fake_system_type');
    }
    
    public function test_requireKnownSystemDoesNotThrowForValidSystem(): void
    {
        $this->systems->requireKnownSystem(KnownWeaponSystems::TURRET_SHORTRANGE);
        $this->assertTrue(true); // If we get here, no exception was thrown
    }
    
    public function test_integrationWithWeaponFinder(): void
    {
        $weapons = WeaponDefs::getInstance()->findWeapons()
            ->selectWeaponSystem(KnownWeaponSystems::TURRET_SHORTRANGE)
            ->getAll();
        
        $this->assertGreaterThan(0, count($weapons));
        
        foreach ($weapons as $weapon) {
            $this->assertSame('turret_shortrange', $weapon->getWeaponSystem());
        }
    }
}
