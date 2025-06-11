<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints;

use AppUtils\ArrayDataCollection;
use AppUtils\Interfaces\StringPrimaryRecordInterface;
use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategories;
use Mistralys\X4\Database\Blueprints\Categories\BlueprintCategoryInterface;
use Mistralys\X4\Database\Races\FactionDef;

abstract class BlueprintDef implements StringPrimaryRecordInterface
{
    const KEY_ID = 'id';
    const KEY_CATEGORY_ID = 'categoryID';

    private string $id;
    private BlueprintCategoryInterface $category;
    private ?FactionDef $race = null;
    private string $label;

    public function __construct(string $id, BlueprintCategoryInterface $category)
    {
        $this->category = $category;
        $this->id = $id;
        $this->label = $id;
    }

    public static function fromArray(array $def) : self
    {
        $data = ArrayDataCollection::create($def);
        $category = BlueprintCategories::getInstance()->getByID($data->getString(self::KEY_CATEGORY_ID));
        $class = $category->getBlueprintClass();

        return new $class(
            $data->getString(self::KEY_ID),
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
