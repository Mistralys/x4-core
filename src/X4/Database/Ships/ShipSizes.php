<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use AppUtils\ClassHelper;
use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\Interfaces\StringPrimaryRecordInterface;
use function AppLocalize\t;

/**
 * Collection of known ship sizes.
 *
 * @package X4 Database
 * @subpackage Ships
 *
 * @method ShipSize[] getAll()
 * @method ShipSize getDefault()
 */
class ShipSizes extends BaseStringPrimaryCollection
{
    public const SIZE_XS = 'xs';
    public const SIZE_S = 's';
    public const SIZE_M = 'm';
    public const SIZE_L = 'l';
    public const SIZE_XL = 'xl';

    private static ?ShipSizes $instance = null;

    public static function getInstance(): ShipSizes
    {
        if (!isset(self::$instance)) {
            self::$instance = new ShipSizes();
        }

        return self::$instance;
    }

    private function getSizeList() : array
    {
        return array(
            self::SIZE_XS => t('Extra Small'),
            self::SIZE_S => t('Small'),
            self::SIZE_M => t('Medium'),
            self::SIZE_L => t('Large'),
            self::SIZE_XL => t('Extra Large'),
        );
    }

    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    protected function registerItems(): void
    {
        foreach($this->getSizeList() as $id => $label) {
            $this->registerItem(new ShipSize($id, $label));
        }
    }

    public function idExists(string $id): bool
    {
        return parent::idExists(self::normalizeID($id));
    }

    /**
     * @param string $id
     * @return ShipSize
     */
    public function getByID(string $id) : StringPrimaryRecordInterface
    {
        return ClassHelper::requireObjectInstanceOf(
            ShipSize::class,
            parent::getByID(self::normalizeID($id))
        );
    }

    /**
     * In some places in the XML data files, ships are identified by their ID
     * with a "ship_" prefix, which is not used here in the database.
     * This method normalizes such IDs to the expected format.
     *
     * @param string $id
     * @return string
     */
    private static function normalizeID(string $id) : string
    {
        if(str_starts_with('ship_', $id)) {
            return substr($id, -1);
        }

        return $id;
    }
}
