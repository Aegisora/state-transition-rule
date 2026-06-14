<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit\Models;

use Aegisora\Rules\StateTransition\Models\StateTransition;
use PHPUnit\Framework\TestCase;

class StateTransitionTest extends TestCase
{
    private static function assertActualStateTransitionEqualsExpected(
        StateTransition $actual,
        array $expectedData
    ): void {
        self::assertSame($expectedData['from']['name'], $actual->getFrom()->getName());
        self::assertSame($expectedData['to']['name'], $actual->getTo()->getName());
    }
}
