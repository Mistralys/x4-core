<?php

declare(strict_types=1);

namespace  X4Tests\Suites\Database\Factions;

use Mistralys\X4\Database\Factions\FactionDefs;
use Mistralys\X4\Database\Factions\KnownFactions;
use X4Tests\Helpers\X4TestCase;

final class KnownFactionTests extends X4TestCase
{
    public function test_allFactionsExistInTheKnownFactionsEnum() : void
    {
        foreach(FactionDefs::getInstance()->getAll() as $faction)
        {
            $this->assertContains($faction->getID(), KnownFactions::FACTIONS);
        }
    }

    // Test all individual faction getter methods

    public function test_getAllianceOfTheWord(): void
    {
        $faction = KnownFactions::getInstance()->getAllianceOfTheWord();
        $this->assertSame(KnownFactions::FACTION_ALLIANCE_WORD, $faction->getID());
    }

    public function test_getAntigoneRepublic(): void
    {
        $faction = KnownFactions::getInstance()->getAntigoneRepublic();
        $this->assertSame(KnownFactions::FACTION_ANTIGONE_REPUBLIC, $faction->getID());
    }

    public function test_getArgonFederation(): void
    {
        $faction = KnownFactions::getInstance()->getArgonFederation();
        $this->assertSame(KnownFactions::FACTION_ARGON_FEDERATION, $faction->getID());
    }

    public function test_getCivilian(): void
    {
        $faction = KnownFactions::getInstance()->getCivilian();
        $this->assertSame(KnownFactions::FACTION_CIVILIAN, $faction->getID());
    }

    public function test_getCourtOfCurbs(): void
    {
        $faction = KnownFactions::getInstance()->getCourtOfCurbs();
        $this->assertSame(KnownFactions::FACTION_COURT_CURBS, $faction->getID());
    }

    public function test_getCriminal(): void
    {
        $faction = KnownFactions::getInstance()->getCriminal();
        $this->assertSame(KnownFactions::FACTION_CRIMINAL, $faction->getID());
    }

    public function test_getDukesBuccaneers(): void
    {
        $faction = KnownFactions::getInstance()->getDukesBuccaneers();
        $this->assertSame(KnownFactions::FACTION_DUKES_BUCCANEERS, $faction->getID());
    }

    public function test_getFallenFamilies(): void
    {
        $faction = KnownFactions::getInstance()->getFallenFamilies();
        $this->assertSame(KnownFactions::FACTION_FALLEN_FAMILIES, $faction->getID());
    }

    public function test_getFreeFamilies(): void
    {
        $faction = KnownFactions::getInstance()->getFreeFamilies();
        $this->assertSame(KnownFactions::FACTION_FREE_FAMILIES, $faction->getID());
    }

    public function test_getGeneric(): void
    {
        $faction = KnownFactions::getInstance()->getGeneric();
        $this->assertSame(KnownFactions::FACTION_GENERIC, $faction->getID());
        $this->assertTrue($faction->isGeneric());
    }

    public function test_getGodrealmOfTheParanid(): void
    {
        $faction = KnownFactions::getInstance()->getGodrealmOfTheParanid();
        $this->assertSame(KnownFactions::FACTION_GODREALM_PARANID, $faction->getID());
    }

    public function test_getHatikvahFreeLeague(): void
    {
        $faction = KnownFactions::getInstance()->getHatikvahFreeLeague();
        $this->assertSame(KnownFactions::FACTION_HATIKVAH_FREE_LEAGUE, $faction->getID());
    }

    public function test_getHolyOrderFaithful(): void
    {
        $faction = KnownFactions::getInstance()->getHolyOrderFaithful();
        $this->assertSame(KnownFactions::FACTION_HOLY_ORDER_FAITHFUL, $faction->getID());
    }

    public function test_getHolyOrderOfThePontifex(): void
    {
        $faction = KnownFactions::getInstance()->getHolyOrderOfThePontifex();
        $this->assertSame(KnownFactions::FACTION_HOLY_ORDER_PONTIFEX, $faction->getID());
    }

    public function test_getKhaak(): void
    {
        $faction = KnownFactions::getInstance()->getKhaak();
        $this->assertSame(KnownFactions::FACTION_KHAAK, $faction->getID());
    }

    public function test_getMinistryOfFinance(): void
    {
        $faction = KnownFactions::getInstance()->getMinistryOfFinance();
        $this->assertSame(KnownFactions::FACTION_MINISTRY_FINANCE, $faction->getID());
    }

    public function test_getOutlaw(): void
    {
        $faction = KnownFactions::getInstance()->getOutlaw();
        $this->assertSame(KnownFactions::FACTION_OUTLAW, $faction->getID());
    }

    public function test_getOwnerless(): void
    {
        $faction = KnownFactions::getInstance()->getOwnerless();
        $this->assertSame(KnownFactions::FACTION_OWNERLESS, $faction->getID());
    }

    public function test_getPlayer(): void
    {
        $faction = KnownFactions::getInstance()->getPlayer();
        $this->assertSame(KnownFactions::FACTION_PLAYER, $faction->getID());
    }

    public function test_getQueendomOfBoron(): void
    {
        $faction = KnownFactions::getInstance()->getQueendomOfBoron();
        $this->assertSame(KnownFactions::FACTION_QUEENDOM_BORON, $faction->getID());
    }

    public function test_getQuettanauts(): void
    {
        $faction = KnownFactions::getInstance()->getQuettanauts();
        $this->assertSame(KnownFactions::FACTION_QUETTANAUTS, $faction->getID());
    }

    public function test_getRealmOfTheTrinity(): void
    {
        $faction = KnownFactions::getInstance()->getRealmOfTheTrinity();
        $this->assertSame(KnownFactions::FACTION_REALM_TRINITY, $faction->getID());
    }

    public function test_getRiptideRakers(): void
    {
        $faction = KnownFactions::getInstance()->getRiptideRakers();
        $this->assertSame(KnownFactions::FACTION_RIPTIDE_RAKERS, $faction->getID());
    }

    public function test_getScalePlatePact(): void
    {
        $faction = KnownFactions::getInstance()->getScalePlatePact();
        $this->assertSame(KnownFactions::FACTION_SCALE_PLATE_PACT, $faction->getID());
    }

    public function test_getSegarisPioneers(): void
    {
        $faction = KnownFactions::getInstance()->getSegarisPioneers();
        $this->assertSame(KnownFactions::FACTION_SEGARIS_PIONEERS, $faction->getID());
    }

    public function test_getSmuggler(): void
    {
        $faction = KnownFactions::getInstance()->getSmuggler();
        $this->assertSame(KnownFactions::FACTION_SMUGGLER, $faction->getID());
    }

    public function test_getTeladiCompany(): void
    {
        $faction = KnownFactions::getInstance()->getTeladiCompany();
        $this->assertSame(KnownFactions::FACTION_TELADI_COMPANY, $faction->getID());
    }

    public function test_getTerranProtectorate(): void
    {
        $faction = KnownFactions::getInstance()->getTerranProtectorate();
        $this->assertSame(KnownFactions::FACTION_TERRAN_PROTECTORATE, $faction->getID());
    }

    public function test_getVigorSyndicate(): void
    {
        $faction = KnownFactions::getInstance()->getVigorSyndicate();
        $this->assertSame(KnownFactions::FACTION_VIGOR_SYNDICATE, $faction->getID());
    }

    public function test_getVisitor(): void
    {
        $faction = KnownFactions::getInstance()->getVisitor();
        $this->assertSame(KnownFactions::FACTION_VISITOR, $faction->getID());
    }

    public function test_getXenon(): void
    {
        $faction = KnownFactions::getInstance()->getXenon();
        $this->assertSame(KnownFactions::FACTION_XENON, $faction->getID());
    }

    public function test_getYaki(): void
    {
        $faction = KnownFactions::getInstance()->getYaki();
        $this->assertSame(KnownFactions::FACTION_YAKI, $faction->getID());
    }

    public function test_getZyarthPatriarchy(): void
    {
        $faction = KnownFactions::getInstance()->getZyarthPatriarchy();
        $this->assertSame(KnownFactions::FACTION_ZYARTH_PATRIARCHY, $faction->getID());
    }
}
