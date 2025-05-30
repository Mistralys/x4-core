<?php

declare(strict_types=1);

namespace X4Tests\Suites\Game;

use Mistralys\X4\Game\X4Game;
use X4Tests\Helpers\X4TestCase;
use const Mistralys\X4\X4_GAME_FOLDER;

final class GameTests extends X4TestCase
{
    public function test_getVersion() : void
    {
        $this->assertNotEmpty(X4Game::create(X4_GAME_FOLDER)->getVersion());
    }
}
