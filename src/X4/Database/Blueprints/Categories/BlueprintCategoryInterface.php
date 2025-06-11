<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Blueprints\Categories;

use AppUtils\Interfaces\StringPrimaryRecordInterface;
use Mistralys\X4\Database\Blueprints\BlueprintDef;
use Mistralys\X4\Database\Blueprints\BlueprintSelection;

interface BlueprintCategoryInterface extends StringPrimaryRecordInterface
{
    public const ERROR_UNKNOWN_BLUEPRINT_ID = 136801;

    /**
     * @return class-string<BlueprintDef>
     */
    public function getBlueprintClass() : string;
    public function createSelection() : BlueprintSelection;
    /**
     * @return BlueprintDef[]
     */
    public function getBlueprints() : array;
    public function countBlueprints() : int;
    /**
     * @param string $blueprintID
     * @return BlueprintDef
     */
    public function getBlueprintByID(string $blueprintID) : BlueprintDef;
    public function blueprintIDExists(string $blueprintID) : bool;
}
