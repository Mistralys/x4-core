<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Ships;

use AppUtils\Collections\BaseStringPrimaryCollection;

/**
 * @method ShipClass getByID(string $id)
 * @method ShipClass[] getAll()
 * @method ShipClass getDefault()
 */
class ShipClasses extends BaseStringPrimaryCollection
{
    public const CLASS_GUNBOAT = 'gunboat';
    public const CLASS_BATTLESHIP = 'battleship';
    public const CLASS_SCOUT = 'scout';
    public const CLASS_FIGHTER = 'fighter';
    public const CLASS_CARRIER = 'carrier';
    public const CLASS_FRIGATE = 'frigate';
    public const CLASS_SCAVENGER = 'scavenger';
    public const CLASS_SCRAPPER = 'scrapper';
    public const CLASS_BUILDER = 'builder';
    public const CLASS_TUG = 'tug';
    public const CLASS_DESTROYER = 'destroyer';
    public const CLASS_HEAVY_FIGHTER = 'heavyfighter';
    public const CLASS_CORVETTE = 'corvette';
    public const CLASS_LARGEMINER = 'largeminer';
    public const CLASS_FREIGHTER = 'freighter';
    public const CLASS_MINER = 'miner';
    public const CLASS_TRANSPORTER = 'transporter';
    public const CLASS_COURIER = 'courier';
    public const CLASS_RESUPPLIER = 'resupplier';
    public const CLASS_EXPEDITIONARY = 'expeditionary';
    public const CLASS_COMPACTOR = 'compactor';

    public const CLASSES = array(
        self::CLASS_MINER => 'Miner',
        self::CLASS_FREIGHTER => 'Freighter',
        self::CLASS_LARGEMINER => 'Large Miner',
        self::CLASS_TRANSPORTER => 'Transporter',
        self::CLASS_DESTROYER => 'Destroyer',
        self::CLASS_GUNBOAT => 'Gunboat',
        self::CLASS_SCAVENGER => 'Scavenger',
        self::CLASS_FRIGATE => 'Frigate',
        self::CLASS_FIGHTER => 'Fighter',
        self::CLASS_HEAVY_FIGHTER => 'Heavy Fighter',
        self::CLASS_SCOUT => 'Scout',
        self::CLASS_COURIER => 'Courier',
        self::CLASS_BUILDER => 'Builder',
        self::CLASS_CARRIER => 'Carrier',
        self::CLASS_RESUPPLIER => 'Resupplier',
        self::CLASS_BATTLESHIP => 'Battleship',
        self::CLASS_CORVETTE => 'Corvette',
        self::CLASS_TUG => 'Tugboat',
        self::CLASS_EXPEDITIONARY => 'Expeditionary',
        self::CLASS_COMPACTOR => 'Compactor',
    );

    private static ?ShipClasses $instance = null;

    public static function getInstance(): ShipClasses
    {
        if (!isset(self::$instance)) {
            self::$instance = new ShipClasses();
        }

        return self::$instance;
    }

    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    protected function registerItems(): void
    {
        foreach(self::CLASSES as $id => $label) {
            $this->registerItem(new ShipClass($id, $label));
        }
    }
}
