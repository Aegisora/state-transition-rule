<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit\Models;

use Aegisora\Rules\StateTransition\Models\StateTransitionMap;
use PHPUnit\Framework\TestCase;

class StateTransitionMapTest extends TestCase
{
    private static function assertActualStateTransitionMapEqualsExpected(
        StateTransitionMap $actual,
        array $expectedData
    ): void {
        self::assertEquals($expectedData['sourceState'], $actual->getSourceState());
        self::assertEquals($expectedData['transitionState'], $actual->getTransitionStates());
    }
}
