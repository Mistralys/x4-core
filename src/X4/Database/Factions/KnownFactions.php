<?php
/**
 * @package X4 Database
 * @subpackage Factions 
 */

declare(strict_types=1);

namespace Mistralys\X4\Database\Factions;

/**
 * This class contains constants and methods for all known
 * factions in X4.
 * 
 * This utility is meant to be used in tandem with the main
 * collection class {@see FactionDefs}. When you want to access
 * items manually in your code, the getter methods are a great
 * help to find what you need.
 * 
 * ## Usage
 * 
 * Use the method {@see self::getInstance()} to get the
 * instance of this class, then call the getter methods to
 * retrieve the items you need.
 * 
 * ----
 * 
 * > NOTE: This is an auto-generated class.
 * 
 * @package X4 Database
 * @subpackage Factions 
 */
class KnownFactions
{
    public const FACTION_ALLIANCE_WORD = 'alliance';
    public const FACTION_ANTIGONE_REPUBLIC = 'antigone';
    public const FACTION_ARGON_FEDERATION = 'argon';
    public const FACTION_CIVILIAN = 'civilian';
    public const FACTION_COURT_CURBS = 'court';
    public const FACTION_CRIMINAL = 'criminal';
    public const FACTION_DUKES_BUCCANEERS = 'buccaneers';
    public const FACTION_FALLEN_FAMILIES = 'fallensplit';
    public const FACTION_FREE_FAMILIES = 'freesplit';
    public const FACTION_GENERIC = 'generic';
    public const FACTION_GODREALM_PARANID = 'paranid';
    public const FACTION_HATIKVAH_FREE_LEAGUE = 'hatikvah';
    public const FACTION_HOLY_ORDER_FAITHFUL = 'holyorderfanatic';
    public const FACTION_HOLY_ORDER_PONTIFEX = 'holyorder';
    public const FACTION_KHAAK = 'khaak';
    public const FACTION_MINISTRY_FINANCE = 'ministry';
    public const FACTION_OWNERLESS = 'ownerless';
    public const FACTION_PLAYER = 'player';
    public const FACTION_QUEENDOM_BORON = 'boron';
    public const FACTION_QUETTANAUTS = 'kaori';
    public const FACTION_REALM_TRINITY = 'trinity';
    public const FACTION_RIPTIDE_RAKERS = 'scavenger';
    public const FACTION_SCALE_PLATE_PACT = 'scaleplate';
    public const FACTION_SEGARIS_PIONEERS = 'pioneers';
    public const FACTION_SMUGGLER = 'smuggler';
    public const FACTION_TELADI_COMPANY = 'teladi';
    public const FACTION_TERRAN_PROTECTORATE = 'terran';
    public const FACTION_VIGOR_SYNDICATE = 'loanshark';
    public const FACTION_VISITOR = 'visitor';
    public const FACTION_XENON = 'xenon';
    public const FACTION_YAKI = 'yaki';
    public const FACTION_ZYARTH_PATRIARCHY = 'split';
    
    public const FACTIONS = array(
        self::FACTION_ALLIANCE_WORD,
        self::FACTION_ANTIGONE_REPUBLIC,
        self::FACTION_ARGON_FEDERATION,
        self::FACTION_CIVILIAN,
        self::FACTION_COURT_CURBS,
        self::FACTION_CRIMINAL,
        self::FACTION_DUKES_BUCCANEERS,
        self::FACTION_FALLEN_FAMILIES,
        self::FACTION_FREE_FAMILIES,
        self::FACTION_GENERIC,
        self::FACTION_GODREALM_PARANID,
        self::FACTION_HATIKVAH_FREE_LEAGUE,
        self::FACTION_HOLY_ORDER_FAITHFUL,
        self::FACTION_HOLY_ORDER_PONTIFEX,
        self::FACTION_KHAAK,
        self::FACTION_MINISTRY_FINANCE,
        self::FACTION_OWNERLESS,
        self::FACTION_PLAYER,
        self::FACTION_QUEENDOM_BORON,
        self::FACTION_QUETTANAUTS,
        self::FACTION_REALM_TRINITY,
        self::FACTION_RIPTIDE_RAKERS,
        self::FACTION_SCALE_PLATE_PACT,
        self::FACTION_SEGARIS_PIONEERS,
        self::FACTION_SMUGGLER,
        self::FACTION_TELADI_COMPANY,
        self::FACTION_TERRAN_PROTECTORATE,
        self::FACTION_VIGOR_SYNDICATE,
        self::FACTION_VISITOR,
        self::FACTION_XENON,
        self::FACTION_YAKI,
        self::FACTION_ZYARTH_PATRIARCHY
    );

    private FactionDefs $defs;

    private function __construct()
    {
        $this->defs = FactionDefs::getInstance();
    }

    private static ?KnownFactions $instance = null;

    public static function getInstance() : KnownFactions
    {
        if (!isset(self::$instance)) {
            self::$instance = new KnownFactions();
        }

        return self::$instance;
    }

    public function getAllianceOfTheWord() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_ALLIANCE_WORD);
    }

    public function getAntigoneRepublic() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_ANTIGONE_REPUBLIC);
    }

    public function getArgonFederation() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_ARGON_FEDERATION);
    }

    public function getCivilian() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_CIVILIAN);
    }

    public function getCourtOfCurbs() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_COURT_CURBS);
    }

    public function getCriminal() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_CRIMINAL);
    }

    public function getDukesBuccaneers() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_DUKES_BUCCANEERS);
    }

    public function getFallenFamilies() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_FALLEN_FAMILIES);
    }

    public function getFreeFamilies() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_FREE_FAMILIES);
    }

    public function getGeneric() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_GENERIC);
    }

    public function getGodrealmOfTheParanid() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_GODREALM_PARANID);
    }

    public function getHatikvahFreeLeague() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_HATIKVAH_FREE_LEAGUE);
    }

    public function getHolyOrderFaithful() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_HOLY_ORDER_FAITHFUL);
    }

    public function getHolyOrderOfThePontifex() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_HOLY_ORDER_PONTIFEX);
    }

    public function getKhaak() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_KHAAK);
    }

    public function getMinistryOfFinance() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_MINISTRY_FINANCE);
    }

    public function getOwnerless() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_OWNERLESS);
    }

    public function getPlayer() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_PLAYER);
    }

    public function getQueendomOfBoron() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_QUEENDOM_BORON);
    }

    public function getQuettanauts() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_QUETTANAUTS);
    }

    public function getRealmOfTheTrinity() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_REALM_TRINITY);
    }

    public function getRiptideRakers() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_RIPTIDE_RAKERS);
    }

    public function getScalePlatePact() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_SCALE_PLATE_PACT);
    }

    public function getSegarisPioneers() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_SEGARIS_PIONEERS);
    }

    public function getSmuggler() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_SMUGGLER);
    }

    public function getTeladiCompany() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_TELADI_COMPANY);
    }

    public function getTerranProtectorate() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_TERRAN_PROTECTORATE);
    }

    public function getVigorSyndicate() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_VIGOR_SYNDICATE);
    }

    public function getVisitor() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_VISITOR);
    }

    public function getXenon() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_XENON);
    }

    public function getYaki() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_YAKI);
    }

    public function getZyarthPatriarchy() : FactionDef
    {
        return $this->defs->getByID(self::FACTION_ZYARTH_PATRIARCHY);
    }
}
