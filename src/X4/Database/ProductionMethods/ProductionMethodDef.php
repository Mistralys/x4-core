<?php
/**
 * @package X4 Database
 * @subpackage Production Methods
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\ProductionMethods;

use AppUtils\ArrayDataCollection;
use AppUtils\Interfaces\StringPrimaryRecordInterface;

/**
 * A ware production method, e.g. "terran" or "recycling". Wares are
 * produced using a method, which determines the races that use it by
 * default and whether the player can build the matching modules.
 *
 * @package X4 Database
 * @subpackage Production Methods
 *
 * @see ProductionMethodDefs
 */
class ProductionMethodDef implements StringPrimaryRecordInterface
{
    public const KEY_ID = 'id';
    public const KEY_LABEL = 'label';
    public const KEY_TAGS = 'tags';
    public const KEY_DEFAULT_RACE_IDS = 'defaultRaceIDs';

    /**
     * Tag set on methods that the player cannot build.
     */
    public const TAG_NO_PLAYER_BUILD = 'noplayerbuild';

    private string $id;
    private string $label;

    /**
     * @var string[]
     */
    private array $tags;

    /**
     * @var string[]
     */
    private array $defaultRaceIDs;

    /**
     * @param string $id
     * @param string $label
     * @param string[] $tags
     * @param string[] $defaultRaceIDs
     */
    public function __construct(string $id, string $label, array $tags = array(), array $defaultRaceIDs = array())
    {
        $this->id = $id;
        $this->label = $label;
        $this->tags = array_values($tags);
        $this->defaultRaceIDs = array_values($defaultRaceIDs);
    }

    /**
     * @param array<string,mixed> $record
     * @return self
     */
    public static function fromArray(array $record) : self
    {
        $data = ArrayDataCollection::create($record);

        return new self(
            $data->getString(self::KEY_ID),
            $data->getString(self::KEY_LABEL),
            $data->getArray(self::KEY_TAGS),
            $data->getArray(self::KEY_DEFAULT_RACE_IDS)
        );
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray() : array
    {
        return array(
            self::KEY_ID => $this->id,
            self::KEY_LABEL => $this->label,
            self::KEY_TAGS => $this->tags,
            self::KEY_DEFAULT_RACE_IDS => $this->defaultRaceIDs
        );
    }

    /**
     * @return string Method ID as used in the ware productions, e.g. "terran".
     */
    public function getID() : string
    {
        return $this->id;
    }

    public function getLabel() : string
    {
        return $this->label;
    }

    /**
     * @return string[]
     */
    public function getTags() : array
    {
        return $this->tags;
    }

    public function hasTag(string $tag) : bool
    {
        return in_array($tag, $this->tags, true);
    }

    /**
     * Whether the player can build production modules that use this method.
     *
     * @return bool
     */
    public function isPlayerBuildable() : bool
    {
        return !$this->hasTag(self::TAG_NO_PLAYER_BUILD);
    }

    /**
     * @return string[] Faction IDs that use the method by default.
     */
    public function getDefaultRaceIDs() : array
    {
        return $this->defaultRaceIDs;
    }
}
