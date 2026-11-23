<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use AppUtils\ArrayDataCollection;
use AppUtils\Interfaces\StringPrimaryRecordInterface;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\CollectionItemTrait;
use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\DataSources\DataSourceDef;
use Mistralys\X4\Database\DataSources\DataSourceDefs;
use Mistralys\X4\Database\MacroIndex\MacroFileDef;
use Mistralys\X4\Database\MacroIndex\MacroFileDefs;
use Mistralys\X4\Database\Factions\FactionDef;
use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\Translations\Language;
use Mistralys\X4\Database\Translations\Languages;

/**
 * Definition of a ware.
 *
 * ## Lazy hydration
 *
 * `WareDefs` loads every ware for all consumers, so the price and
 * production data is kept as raw records and the value objects
 * ({@see WarePrice}, {@see WareProduction}) are built on first access of
 * {@see self::getPrice()}, {@see self::getProductions()} or
 * {@see self::getProduction()}, then cached. The constructor accepts both
 * value objects and records, and normalizes them to records.
 * {@see self::hasPrice()}, {@see self::getProductionMethodIDs()},
 * {@see self::getProductionResourceIDs()} and {@see self::usesResource()}
 * read the records only and never build any objects.
 *
 * @package X4 Database
 * @subpackage Wares
 */
class WareDef implements CollectionItemInterface
{
    use CollectionItemTrait;

    public const KEY_WARE_ID = 'wareID';
    public const KEY_LABEL = 'label';
    public const KEY_GROUP = 'group';
    public const KEY_TAGS = 'tags';
    public const KEY_DATA_SOURCE_ID = 'dataSourceID';
    public const KEY_SIZE = 'size';
    public const KEY_FACTIONS = 'factions';
    public const KEY_MACRO_ID = 'macroID';
    public const KEY_VARIANT_ID = 'variantID';
    public const KEY_COMPONENT = 'component';
    public const KEY_PRICE_MIN = 'priceMin';
    public const KEY_PRICE_AVERAGE = 'priceAverage';
    public const KEY_PRICE_MAX = 'priceMax';
    public const KEY_PRODUCTIONS = 'productions';
    public const KEY_LICENCE_ID = 'licenceID';
    public const KEY_DESCRIPTION_ID = 'descriptionID';
    public const KEY_ILLEGAL_FACTION_IDS = 'illegalFactionIDs';
    public const KEY_BUY_RESTRICTED = 'buyRestricted';
    public const KEY_VOLUME = 'volume';
    public const KEY_TRANSPORT_TYPE_ID = 'transportTypeID';

    private string $id;
    private string $label;
    private string $groupID;

    /**
     * The tags associated with this ware.
     * @var string[]
     */
    private array $tags;
    private string $dataSourceID;
    private string $size;

    /**
     * The IDs of the factions that own this ware.
     * @var string[]
     */
    private array $factionIDs;
    private string $macroID;
    private VariantID $variantID;
    
    /**
     * @var array{tags:string[]}
     */
    private array $component;

    /**
     * Raw price record (the three flat `price*` keys), or null.
     * @var array<string,int>|null
     */
    private ?array $priceRecord;

    /**
     * Raw production records.
     * @var array<int,array<string,mixed>>
     */
    private array $productionRecords;

    private ?string $licenceID;
    private ?string $descriptionID;

    /**
     * @var string[]
     */
    private array $illegalFactionIDs;
    private bool $buyRestricted;
    private ?int $volume;
    private ?string $transportTypeID;

    private ?WarePrice $priceObject = null;

    /**
     * @var WareProduction[]|null
     */
    private ?array $productionObjects = null;

    /**
     * @param string $id
     * @param string $macroID
     * @param string $label
     * @param string $groupID
     * @param VariantID $variantID
     * @param string[] $tags
     * @param string $dataSourceID
     * @param string $size
     * @param string[] $factionIDs
     * @param array{tags:string[]} $component
     * @param WarePrice|array<string,mixed>|null $price Value object or raw record (flat `price*` keys).
     * @param array<int,WareProduction|array<string,mixed>> $productions Value objects or raw records.
     * @param string|null $licenceID
     * @param string|null $descriptionID
     * @param string[] $illegalFactionIDs
     * @param bool $buyRestricted
     * @param int|null $volume
     * @param string|null $transportTypeID
     */
    public function __construct(
        string $id, 
        string $macroID, 
        string $label, 
        string $groupID, 
        VariantID $variantID, 
        array $tags, 
        string $dataSourceID, 
        string $size,
        array $factionIDs,
        array $component,
        WarePrice|array|null $price = null,
        array $productions = array(),
        ?string $licenceID = null,
        ?string $descriptionID = null,
        array $illegalFactionIDs = array(),
        bool $buyRestricted = false,
        ?int $volume = null,
        ?string $transportTypeID = null
    ) {
        $this->id = $id;
        $this->label = $label;
        $this->groupID = $groupID;
        $this->macroID = $macroID;
        $this->variantID = $variantID;
        $this->tags = $tags;
        $this->dataSourceID = $dataSourceID;
        $this->size = $size;
        $this->factionIDs = $factionIDs;
        $this->component = $component;
        $this->licenceID = $licenceID;
        $this->descriptionID = $descriptionID;
        $this->illegalFactionIDs = array_values($illegalFactionIDs);
        $this->buyRestricted = $buyRestricted;
        $this->volume = $volume;
        $this->transportTypeID = $transportTypeID;

        if($price instanceof WarePrice) {
            $price = $price->toArray();
        }

        $this->priceRecord = !empty($price) ? $price : null;

        $this->productionRecords = array();
        foreach($productions as $production) {
            $this->productionRecords[] = $production instanceof WareProduction ? $production->toArray() : $production;
        }
    }

    public function getID() : string
    {
        return $this->id;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    public function getVariantID(): VariantID
    {
        return $this->variantID;
    }

    public function getWare(): WareDef
    {
        return $this;
    }

    public function getGroupID() : string
    {
        return $this->groupID;
    }

    public function getGroup() : WareGroup
    {
        return WareGroups::getInstance()->getByID($this->getGroupID());
    }

    /**
     * Get the tags associated with this ware.
     * @return string[]
     */
    public function getTags() : array
    {
        return $this->tags;
    }

    public function getDataSourceID() : string
    {
        return $this->dataSourceID;
    }

    /**
     * Gets information on the original game data source that this ware was extracted from.
     * @return DataSourceDef
     */
    public function getDataSource() : DataSourceDef
    {
        return DataSourceDefs::getInstance()->getByID($this->getDataSourceID());
    }

    public function getMacroID() : string
    {
        return $this->macroID;
    }

    public function getMacro() : MacroFileDef
    {
        return MacroFileDefs::getInstance()->getByMacroName(
            $this->getMacroID(),
            $this->getDataSourceID()
        );
    }

    /**
     * Get the IDs of the factions that typically use this ware.
     * @return string[]
     */
    public function getFactionIDs() : array
    {
        return $this->factionIDs;
    }

    /**
     * Gets the factions that typically use this ware.
     * @return FactionDef[]
     */
    public function getFactions() : array
    {
        $defs = FactionDefs::getInstance();
        $result = array();
        foreach($this->factionIDs as $factionID) {
            $result[] = $defs->getByID($factionID);
        }

        usort($result, static function(FactionDef $a, FactionDef $b) : int {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });

        return $result;
    }

    public static function fromArray(array $wareDef) : WareDef
    {
        $data = ArrayDataCollection::create($wareDef);

        return new WareDef(
            $data->getString(self::KEY_WARE_ID),
            $data->getString(self::KEY_MACRO_ID, ''),
            $data->getString(self::KEY_LABEL),
            $data->getString(self::KEY_GROUP),
            VariantID::fromID($data->getString(self::KEY_VARIANT_ID, '::')),
            $data->getArray(self::KEY_TAGS, []),
            $data->getString(self::KEY_DATA_SOURCE_ID),
            $data->getString(self::KEY_SIZE, ''),
            $data->getArray(self::KEY_FACTIONS, []),
            $data->getArray(self::KEY_COMPONENT, ['tags' => []]),
            self::extractPriceRecord($wareDef),
            $data->getArray(self::KEY_PRODUCTIONS, []),
            $data->getString(self::KEY_LICENCE_ID, '') ?: null,
            $data->getString(self::KEY_DESCRIPTION_ID, '') ?: null,
            $data->getArray(self::KEY_ILLEGAL_FACTION_IDS, []),
            $data->getBool(self::KEY_BUY_RESTRICTED),
            isset($wareDef[self::KEY_VOLUME]) ? (int)$wareDef[self::KEY_VOLUME] : null,
            $data->getString(self::KEY_TRANSPORT_TYPE_ID, '') ?: null
        );
    }

    /**
     * @param array<string,mixed> $wareDef
     * @return array<string,int>|null
     */
    private static function extractPriceRecord(array $wareDef) : ?array
    {
        $record = array();

        foreach(array(self::KEY_PRICE_MIN, self::KEY_PRICE_AVERAGE, self::KEY_PRICE_MAX) as $key) {
            if(!isset($wareDef[$key])) {
                return null;
            }

            $record[$key] = (int)$wareDef[$key];
        }

        return $record;
    }

    /**
     * Gets the reference price of the ware. The object is built
     * on first access and cached.
     *
     * @return WarePrice|null Null if the ware has no price.
     */
    public function getPrice() : ?WarePrice
    {
        if($this->priceRecord === null) {
            return null;
        }

        if($this->priceObject === null) {
            $this->priceObject = WarePrice::fromArray($this->priceRecord);
        }

        return $this->priceObject;
    }

    /**
     * Whether the ware has a price. Does not build the price object.
     */
    public function hasPrice() : bool
    {
        return $this->priceRecord !== null;
    }

    /**
     * Gets the production recipes of the ware, including those added by
     * DLC patches. The objects are built on first access and cached.
     *
     * @return WareProduction[]
     */
    public function getProductions() : array
    {
        if($this->productionObjects === null) {
            $this->productionObjects = array();
            foreach($this->productionRecords as $record) {
                $this->productionObjects[] = WareProduction::fromArray($record);
            }
        }

        return $this->productionObjects;
    }

    /**
     * Gets the first production recipe that uses the given method.
     *
     * @param string $methodID
     * @return WareProduction|null
     */
    public function getProduction(string $methodID) : ?WareProduction
    {
        foreach($this->getProductions() as $production) {
            if($production->methodID === $methodID) {
                return $production;
            }
        }

        return null;
    }

    /**
     * Gets the IDs of the methods the ware can be produced with.
     * Reads the raw records and does not build production objects.
     *
     * @return string[]
     */
    public function getProductionMethodIDs() : array
    {
        $result = array();
        foreach($this->productionRecords as $record) {
            $result[] = (string)($record[WareProduction::KEY_METHOD_ID] ?? '');
        }

        return $result;
    }

    /**
     * Gets the IDs of all resource wares used by any production recipe,
     * sorted and unique. Reads the raw records and does not build production objects.
     *
     * @return string[]
     */
    public function getProductionResourceIDs() : array
    {
        $result = array();
        foreach($this->productionRecords as $record) {
            foreach(array_keys($record[WareProduction::KEY_RESOURCES] ?? array()) as $wareID) {
                $result[(string)$wareID] = true;
            }
        }

        $ids = array_map('strval', array_keys($result));
        sort($ids);

        return $ids;
    }

    /**
     * Whether any production recipe of this ware consumes the given ware.
     * Reads the raw records and does not build production objects.
     *
     * @param string $wareID
     * @return bool
     */
    public function usesResource(string $wareID) : bool
    {
        foreach($this->productionRecords as $record) {
            if(isset($record[WareProduction::KEY_RESOURCES][$wareID])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the licence type key required to buy the ware, if any.
     * The key is defined per faction in `libraries/factions.xml`;
     * there is no licence collection.
     */
    public function getLicenceID() : ?string
    {
        return $this->licenceID;
    }

    /**
     * Gets the translation code (`{page,id}`) of the ware description.
     */
    public function getDescriptionID() : ?string
    {
        return $this->descriptionID;
    }

    /**
     * Gets the ware description. A language that lacks the text falls
     * back to English. The raw text code is never returned.
     *
     * @param Language|null $language Defaults to English.
     * @return string Empty if the ware has no description, or if neither the language nor English has the text.
     */
    public function getDescription(?Language $language = null) : string
    {
        if(empty($this->descriptionID)) {
            return '';
        }

        $language ??= Languages::getInstance()->getEnglish();

        return $language->tsWithFallback($this->descriptionID);
    }

    /**
     * The space one unit of the ware takes in a ship's cargo hold,
     * in the same unit as the cargo capacity of ships.
     */
    public function getVolume() : ?int
    {
        return $this->volume;
    }

    /**
     * Gets the transport type ID (the `transport` attribute of the game),
     * see {@see WareTransportTypes}. Note that `container` is not limited
     * to commodities (it includes station modules, for example).
     */
    public function getTransportTypeID() : ?string
    {
        return $this->transportTypeID;
    }

    /**
     * @return string[]
     */
    public function getIllegalFactionIDs() : array
    {
        return $this->illegalFactionIDs;
    }

    /**
     * Whether the ware is illegal for the given faction.
     *
     * @param string|FactionDef $faction
     * @return bool
     */
    public function isIllegalFor(string|FactionDef $faction) : bool
    {
        $id = $faction instanceof FactionDef ? $faction->getID() : $faction;

        return in_array($id, $this->illegalFactionIDs, true);
    }

    /**
     * Gets the factions that consider this ware illegal.
     *
     * @return FactionDef[]
     */
    public function getIllegalFactions() : array
    {
        $defs = FactionDefs::getInstance();
        $result = array();
        foreach($this->illegalFactionIDs as $factionID) {
            $result[] = $defs->getByID($factionID);
        }

        usort($result, static function(FactionDef $a, FactionDef $b) : int {
            return strnatcasecmp($a->getLabel(), $b->getLabel());
        });

        return $result;
    }

    /**
     * Whether the ware carries the raw game flag `<restriction buy="1">`.
     * The game data does not document its meaning.
     */
    public function hasBuyRestriction() : bool
    {
        return $this->buyRestricted;
    }

    public function hasTag(string $tag) : bool
    {
        return in_array($tag, $this->tags, true);
    }
    
    /**
     * Gets the component data (compatibility tags from the physical component).
     * @return array{tags:string[]}
     */
    public function getComponent() : array
    {
        return $this->component;
    }
    
    /**
     * @deprecated Use getComponent() instead
     * @return array{tags:string[]}
     */
    public function getSpecs() : array
    {
        return $this->getComponent();
    }
    
    public function getSize() : string
    {
        return $this->size;
    }
    
    /**
     * @return string[]
     */
    public function getCompatibilityTags() : array
    {
        $componentTags = $this->component['tags'] ?? [];
        $wareTags = $this->getTags();

        return array_unique(array_merge($componentTags, $wareTags));
    }

    public function toArray() : array
    {
        $result = array(
            self::KEY_WARE_ID => $this->getID(),
            self::KEY_LABEL => $this->getLabel(),
            self::KEY_GROUP => $this->getGroupID(),
            self::KEY_DATA_SOURCE_ID => $this->getDataSourceID(),
        );
        
        // Only include size if not empty
        if (!empty($this->getSize())) {
            $result[self::KEY_SIZE] = $this->getSize();
        }
        
        // Only include tags if not empty
        if (!empty($this->getTags())) {
            $result[self::KEY_TAGS] = $this->getTags();
        }
        
        // Only include macroID if not empty
        if (!empty($this->getMacroID())) {
            $result[self::KEY_MACRO_ID] = $this->getMacroID();
        }
        
        // Only include variantID if not default "::"
        if ($this->getVariantID()->getID() !== '::') {
            $result[self::KEY_VARIANT_ID] = $this->getVariantID()->getID();
        }
        
        // Only include factions if not empty
        if (!empty($this->getFactionIDs())) {
            $result[self::KEY_FACTIONS] = $this->getFactionIDs();
        }
        
        // Only include component if tags is not empty
        $component = $this->getComponent();
        if (!empty($component['tags'])) {
            $result[self::KEY_COMPONENT] = $component;
        }

        if($this->priceRecord !== null) {
            $result = array_merge($result, $this->priceRecord);
        }

        if(!empty($this->productionRecords)) {
            $result[self::KEY_PRODUCTIONS] = $this->productionRecords;
        }

        if(!empty($this->licenceID)) {
            $result[self::KEY_LICENCE_ID] = $this->licenceID;
        }

        if(!empty($this->descriptionID)) {
            $result[self::KEY_DESCRIPTION_ID] = $this->descriptionID;
        }

        if(!empty($this->illegalFactionIDs)) {
            $result[self::KEY_ILLEGAL_FACTION_IDS] = $this->illegalFactionIDs;
        }

        if($this->buyRestricted) {
            $result[self::KEY_BUY_RESTRICTED] = true;
        }

        if($this->volume !== null) {
            $result[self::KEY_VOLUME] = $this->volume;
        }

        if(!empty($this->transportTypeID)) {
            $result[self::KEY_TRANSPORT_TYPE_ID] = $this->transportTypeID;
        }
        
        return $result;
    }
}
