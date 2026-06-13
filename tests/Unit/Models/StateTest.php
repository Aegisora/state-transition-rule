<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit\Models;

use Aegisora\Rules\StateTransition\Models\State;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    private static function assertActualStateEqualsExpected(
        State $actual,
        array $expectedData
    ): void {
        self::assertSame($expectedData['name'], $actual->getName());
    }
}
