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
}
