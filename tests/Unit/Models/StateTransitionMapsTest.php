<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit\Models;

use Aegisora\Rules\StateTransition\Models\StateTransitionMaps;
use PHPUnit\Framework\TestCase;

class StateTransitionMapsTest extends TestCase
{
    private static function assertActualStateTransitionMapsEqualsExpected(
        StateTransitionMaps $actual,
        array $expectedData
    ): void {
        self::assertEquals($expectedData['maps'], $actual->getMaps());
    }
}
