<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit;

use Aegisora\RuleContract\RuleInterface;
use Aegisora\Rules\StateTransition\Models\StateTransitionMaps;
use Aegisora\Rules\StateTransition\StateTransitionRule;
use PHPUnit\Framework\TestCase;

class StateTransitionRuleTest extends TestCase
{
    public function testCreate(): void
    {
        self::assertInstanceOf(RuleInterface::class, StateTransitionRule::create(StateTransitionMaps::create([])));
    }
}
