<?php
/**
 * @package X4 Database
 * @subpackage Ships
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships\Equipment;

use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\Finder\BaseFinder;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionInterface;
use Mistralys\X4\Database\Core\Finder\DataSourceSelectionTrait;
use Mistralys\X4\Database\Core\ItemCollectionInterface;
use Mistralys\X4\Database\Ships\ShipDef;
use Mistralys\X4\Database\SlotTypes\KnownSlotTypes;
use Mistralys\X4\Database\Wares\WareDef;
use Mistralys\X4\Database\Wares\WareDefs;
use Mistralys\X4\Database\Wares\WareGroups;

/**
 * Specialized filtering utility to find equipment compatible with a specific
 * ship and slot type. Provides fluent interface for filtering by data source,
 * size, tags, and other criteria.
 *
 * Example usage:
 * ```php
 * $ship = ShipDefs::getInstance()->getByID('ship_arg_l_destroyer_01_a');
 * $engines = $ship->getEngines()
 *     ->selectDataSource(KnownDataSources::DATA_SOURCE_VANILLA)
 *     ->selectSize('l')
 *     ->getAll();
 * ```
 *
 * @package X4 Database
 * @subpackage Ships
 */
class ShipEquipmentFinder extends BaseFinder implements DataSourceSelectionInterface
{
    use DataSourceSelectionTrait;

    private ShipDef $ship;
    private string $slotTypeID;

    /**
     * @var string[] Size filters (e.g., 's', 'm', 'l', 'xl')
     */
    private array $sizes = array();

    /**
     * @var string[] Tag filters
     */
    private array $tags = array();

    /**
     * Map slot type IDs to ware group IDs for efficient pre-filtering
     */
    private const SLOT_TO_GROUP_MAP = [
        KnownSlotTypes::ENGINE => WareGroups::GROUP_ENGINES,
        KnownSlotTypes::SHIELD => WareGroups::GROUP_SHIELDS,
        KnownSlotTypes::WEAPON => WareGroups::GROUP_WEAPONS,
        KnownSlotTypes::TURRET => WareGroups::GROUP_TURRETS,
        KnownSlotTypes::COUNTERMEASURES => WareGroups::GROUP_COUNTERMEASURES,
        KnownSlotTypes::DOCKING_BAY => '' // Docking bays don't have a ware group
    ];

    /**
     * @param ShipDef $ship The ship to find compatible equipment for
     * @param string $slotTypeID The slot type ID (from KnownSlotTypes constants)
     */
    public function __construct(ShipDef $ship, string $slotTypeID)
    {
        $this->ship = $ship;
        $this->slotTypeID = $slotTypeID;
    }

    public function getCollection(): ItemCollectionInterface
    {
        return WareDefs::getInstance();
    }

    /**
     * Filter by equipment size.
     * @param string $size Size code: 's', 'm', 'l', 'xl'
     * @return $this
     */
    public function selectSize(string $size): self
    {
        if (!in_array($size, $this->sizes, true)) {
            $this->sizes[] = $size;
        }

        return $this;
    }

    /**
     * Filter by ware tag.
     * @param string $tag Tag name
     * @return $this
     */
    public function selectTag(string $tag): self
    {
        if (!in_array($tag, $this->tags, true)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * Check if a ware matches all filter criteria.
     *
     * @param CollectionItemInterface|WareDef $item
     * @return bool
     */
    protected function isMatch(CollectionItemInterface $item): bool
    {
        // Only consider equipment wares
        if (!$item->hasTag('equipment')) {
            return false;
        }

        // Filter by ware group if we have a mapping for this slot type
        $expectedGroup = self::SLOT_TO_GROUP_MAP[$this->slotTypeID] ?? '';
        if ($expectedGroup !== '' && $item->getGroupID() !== $expectedGroup) {
            return false;
        }

        // Check ship compatibility
        if (!$this->ship->canEquip($item)) {
            return false;
        }

        // Check data source filter
        if (!$this->isDataSourceMatch($item->getDataSourceID())) {
            return false;
        }

        // Check label search filter
        if (!$this->isLabelMatch($item->getLabel())) {
            return false;
        }

        // Check size filters
        if (!empty($this->sizes) && !in_array($item->getSize(), $this->sizes, true)) {
            return false;
        }

        // Check tag filters (all must be present)
        foreach ($this->tags as $tag) {
            if (!in_array($tag, $item->getTags(), true)) {
                return false;
            }
        }

        return true;
    }
}
