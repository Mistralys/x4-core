<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints;

use AppUtils\ArrayDataCollection;
use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategoryInterface;
use Mistralys\X4\Database\Core\CollectionItemInterface;
use Mistralys\X4\Database\Core\CollectionItemTrait;
use Mistralys\X4\Database\Core\VariantID;
use Mistralys\X4\Database\Factions\FactionDef;

abstract class BlueprintDef implements CollectionItemInterface
{
    use CollectionItemTrait;

    public const KEY_WARE_ID = 'wareID';
    public const KEY_LABEL = 'label';
    public const KEY_CATEGORY_ID = 'categoryID';
    public const KEY_VARIANT_ID = 'variantID';

    private string $id;
    private BlueprintCategoryInterface $category;
    private ?FactionDef $race = null;
    private string $label;
    private VariantID $variantID;

    public function __construct(string $id, string $label, VariantID $variantID, BlueprintCategoryInterface $category)
    {
        $this->category = $category;
        $this->id = $id;
        $this->label = $label;
        $this->variantID = $variantID;

        $this->init();
    }

    protected function init() : void
    {

    }

    public static function fromArray(array $def) : self
    {
        $data = ArrayDataCollection::create($def);
        $category = BlueprintCategories::getInstance()->getByID($data->getString(self::KEY_CATEGORY_ID));
        $class = $category->getBlueprintClass();

        return new $class(
            $data->getString(self::KEY_WARE_ID),
            $data->getString(self::KEY_LABEL),
            VariantID::fromID($data->getString(self::KEY_VARIANT_ID)),
            $category
        );
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

    abstract public function getTypeLabel() : string;

    public function getCategory(): BlueprintCategoryInterface
    {
        return $this->category;
    }

    public function getCategoryID() : string
    {
        return $this->category->getID();
    }

    /**
     * @return string
     * @deprecated Use {@see self::getID()} instead.
     */
    public function getName(): string
    {
        return $this->id;
    }

    public function getRace() : ?FactionDef
    {
        return $this->race;
    }

    public function getRaceID() : string
    {
        return $this->race->getID();
    }
}
