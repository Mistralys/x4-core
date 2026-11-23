<?php
/**
 * @package X4 Database
 * @subpackage Wares
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Wares;

use AppUtils\ArrayDataCollection;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodDef;
use Mistralys\X4\Database\ProductionMethods\ProductionMethodDefs;

/**
 * A production recipe of a ware (`<production>` in `wares.xml`).
 *
 * The game attribute `time` is called `duration` here, and `amount`
 * is called `quantity`, so that neither is confused with the resource amounts.
 *
 * @package X4 Database
 * @subpackage Wares
 */
final class WareProduction
{
    public const KEY_METHOD_ID = 'methodID';
    public const KEY_DURATION = 'duration';
    public const KEY_QUANTITY = 'quantity';
    public const KEY_RESOURCES = 'resources';
    public const KEY_EFFECTS = 'effects';

    public readonly string $methodID;

    /**
     * Seconds per production cycle.
     */
    public readonly float $duration;

    /**
     * Units produced per cycle.
     */
    public readonly int $quantity;

    /**
     * Ware ID => amount consumed per cycle.
     * @var array<string,int>
     */
    public readonly array $resources;

    /**
     * Effect type (`work`, `sunlight`, `efficiency`) => value.
     * @var array<string,float>
     */
    public readonly array $effects;

    /**
     * @param string $methodID
     * @param float $duration
     * @param int $quantity
     * @param array<string,int> $resources
     * @param array<string,float> $effects
     */
    public function __construct(string $methodID, float $duration, int $quantity, array $resources = array(), array $effects = array())
    {
        $this->methodID = $methodID;
        $this->duration = $duration;
        $this->quantity = $quantity;
        $this->resources = $resources;
        $this->effects = $effects;
    }

    /**
     * @param array<string,mixed> $record
     * @return WareProduction
     */
    public static function fromArray(array $record) : WareProduction
    {
        $data = ArrayDataCollection::create($record);

        $resources = array();
        foreach($data->getArray(self::KEY_RESOURCES, array()) as $wareID => $amount) {
            $resources[(string)$wareID] = (int)$amount;
        }

        $effects = array();
        foreach($data->getArray(self::KEY_EFFECTS, array()) as $type => $value) {
            $effects[(string)$type] = (float)$value;
        }

        return new WareProduction(
            $data->getString(self::KEY_METHOD_ID),
            (float)($record[self::KEY_DURATION] ?? 0),
            $data->getInt(self::KEY_QUANTITY),
            $resources,
            $effects
        );
    }

    /**
     * @return array{methodID:string,duration:float,quantity:int,resources:array<string,int>,effects:array<string,float>}
     */
    public function toArray() : array
    {
        return array(
            self::KEY_METHOD_ID => $this->methodID,
            self::KEY_DURATION => $this->duration,
            self::KEY_QUANTITY => $this->quantity,
            self::KEY_RESOURCES => $this->resources,
            self::KEY_EFFECTS => $this->effects,
        );
    }

    /**
     * Gets the production method this recipe uses.
     *
     * @return ProductionMethodDef|null Null if the method ID is not known.
     */
    public function getMethod() : ?ProductionMethodDef
    {
        $methods = ProductionMethodDefs::getInstance();

        if($methods->idExists($this->methodID)) {
            return $methods->getByID($this->methodID);
        }

        return null;
    }

    /**
     * Gets the ware definitions of the resources consumed by this
     * recipe. Resource IDs that cannot be resolved are skipped.
     *
     * @return WareDef[]
     */
    public function getResourceWares() : array
    {
        $wares = WareDefs::getInstance();
        $result = array();

        foreach(array_keys($this->resources) as $wareID) {
            $ware = $wares->findByMacro((string)$wareID);
            if($ware !== null) {
                $result[] = $ware;
            }
        }

        return $result;
    }

    /**
     * Gets the cost of the resources consumed in one production cycle:
     * the sum of `amount x average price` over all resources.
     *
     * Limits: the basis is the reference average price of the game
     * ({@see WarePrice::$average}), not a market price. This is the
     * cost of the inputs only: the effects (work, sunlight, efficiency)
     * and the production duration are not factored in, and it is not
     * a profit calculation.
     *
     * @return int|null Null if a resource is unknown or has no price. 0 for a production without resources.
     */
    public function getResourceCost() : ?int
    {
        $wares = WareDefs::getInstance();
        $total = 0;

        foreach($this->resources as $wareID => $amount) {
            $price = $wares->findByMacro((string)$wareID)?->getPrice();

            if($price === null) {
                return null;
            }

            $total += $amount * $price->average;
        }

        return $total;
    }

    /**
     * Gets the resource cost per produced unit: {@see self::getResourceCost()}
     * divided by the `quantity` of the cycle. The same limits apply: reference
     * average prices, input cost only, no effects or production duration.
     *
     * @return float|null Null if the cost is null or the quantity is 0.
     */
    public function getResourceCostPerUnit() : ?float
    {
        $cost = $this->getResourceCost();

        if($cost === null || $this->quantity === 0) {
            return null;
        }

        return $cost / $this->quantity;
    }
}
