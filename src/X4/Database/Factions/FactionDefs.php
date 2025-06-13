<?php

declare(strict_types=1);

namespace Mistralys\X4\Database\Factions;

use AppUtils\Collections\BaseStringPrimaryCollection;
use AppUtils\FileHelper\JSONFile;
use Mistralys\X4\Database\Core\ItemCollectionInterface;
use Mistralys\X4\X4Application;

/**
 * @method FactionDef getByID(string $id)
 * @method FactionDef[] getAll()
 * @method FactionDef getDefault()
 */
class FactionDefs extends BaseStringPrimaryCollection implements ItemCollectionInterface
{
    public const SHORT_ID_ATF = 'atf';
    public const SHORT_ID_PIR = 'pir';

    public const SHORT_ID_MAPPINGS = array(
        self::SHORT_ID_ATF => KnownFactions::FACTION_TERRAN_PROTECTORATE,
        self::SHORT_ID_PIR => KnownFactions::FACTION_RIPTIDE_RAKERS,
    );

    private static ?FactionDefs $instance = null;

    private JSONFile $dataFile;

    private function __construct()
    {
        $this->dataFile = JSONFile::factory(X4Application::getDataFolder() .'/factions.json')
            ->setPrettyPrint(true)
            ->setTrailingNewline(true)
            ->setEscapeSlashes(false);
    }

    /**
     * Attempts to detect the faction ID from a macro or component ID.
     *
     * For example, the macro ID "arg_mining_station_macro" would return
     * the "Argon" faction. For this, the faction short IDs are used
     * ({@see FactionDef::getShortIDs()}).
     *
     * @param string $macroOrComponentID
     * @return string|null
     */
    public function detectFactionByID(string $macroOrComponentID) : ?string
    {
        $parts = explode('_', $macroOrComponentID);

        foreach($this->getAll() as $faction) {
            foreach($faction->getShortIDs() as $shortID) {
                if(in_array($shortID, $parts, true)) {
                    return $faction->getID();
                }
            }
        }

        return null;
    }

    public function getDataFile(): JSONFile
    {
        return $this->dataFile;
    }

    public static function getInstance() : FactionDefs
    {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getFromList() : KnownFactions
    {
        return KnownFactions::getInstance();
    }

    public function getDefaultID(): string
    {
        return $this->getAutoDefault();
    }

    protected function registerItems(): void
    {
        foreach($this->getDataFile()->getData() as $raceDef) {
            $this->registerItem(FactionDef::fromArray($raceDef));
        }
    }
}
