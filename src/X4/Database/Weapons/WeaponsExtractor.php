<?php
/**
 * @package X4Core
 * @see \Mistralys\X4\Database\Weapons\WeaponsExtractor
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Weapons;

use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\WeaponSystems\WeaponSystems;
use Mistralys\X4\ExtractedData\DataFolders;
use Mistralys\X4\UI\Console;

/**
 * Extracts weapon performance data from X4 macro XML files.
 * 
 * Process:
 * 1. Filter WareDefs to get only weapons (tag = "weapon" or "turret")
 * 2. For each weapon wareID:
 *    - Access weapon macro XML via WareDef
 *    - Extract weapon properties (heat, rotation, hull)
 *    - Load bullet macro XML
 *    - Extract bullet properties (damage, reload, speed, etc.)
 * 3. Write to data/weapons.json
 *
 * @package X4Core
 * @subpackage Database
 */
class WeaponsExtractor
{
    /**
     * @var array<int,array<string,mixed>>
     */
    private array $weapons = array();

    public function __construct(DataFolders $dataFolders)
    {
        // DataFolders passed for consistency with other extractors
    }

    /**
     * Main extraction method.
     * Generates data/weapons.json.
     */
    public function extract(): void
    {
        Console::header('Extracting weapons...');

        // Filter wares to get weapons and turrets
        foreach (WareDefs::getInstance()->getAll() as $ware) {
            if ($this->isWeapon($ware)) {
                $this->processWare($ware);
            }
        }

        Console::line1('Found [%d] weapons.', count($this->weapons));
        Console::line1('Saving to disk...');
        Console::nl();

        // Sort by ware ID for consistent output
        usort($this->weapons, fn($a, $b) => $a[WeaponDef::KEY_WARE_ID] <=> $b[WeaponDef::KEY_WARE_ID]);

        WeaponDefs::getInstance()
            ->getDataFile()
            ->putData($this->weapons);
    }

    /**
     * Check if a ware is a weapon or turret.
     * 
     * @param WareDef $ware
     * @return bool
     */
    private function isWeapon(WareDef $ware): bool
    {
        $tags = $ware->getTags();
        return in_array('weapon', $tags, true) || in_array('turret', $tags, true);
    }

    /**
     * Process a single ware to extract weapon data.
     * 
     * @param WareDef $ware
     */
    private function processWare(WareDef $ware): void
    {
        try {
            $weaponData = (new WeaponMacroExtractor($ware))->extract();
            
            // Validate weapon system type
            $weaponSystem = $weaponData[WeaponDef::KEY_WEAPON_SYSTEM] ?? '';
            if (!empty($weaponSystem)) {
                WeaponSystems::getInstance()->requireKnownSystem($weaponSystem);
            }
            
            $this->weapons[] = $weaponData;
        } catch (\Exception $e) {
            Console::line1('  - WARNING | Failed to extract weapon [%s]: %s', $ware->getID(), $e->getMessage());
        }
    }
}
