<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit\Models;

use Aegisora\Rules\StateTransition\Models\StateTransition;
use PHPUnit\Framework\TestCase;

class StateTransitionTest extends TestCase
{
    /**
     * @dataProvider getCreateStateTransitionProvidedData
     */
    public function testCreate(
        array $actualData,
        array $expectedData
    ): void {
        self::assertActualStateTransitionEqualsExpected(new StateTransition(...array_values($actualData)), $expectedData);
        self::assertActualStateTransitionEqualsExpected(StateTransition::create(...array_values($actualData)), $expectedData);
    }

    public static function getCreateStateTransitionProvidedData(): array
    {
        return [];
    }

    private static function assertActualStateTransitionEqualsExpected(
        StateTransition $actual,
        array $expectedData
    ): void {
        self::assertSame($expectedData['from']['name'], $actual->getFrom()->getName());
        self::assertSame($expectedData['to']['name'], $actual->getTo()->getName());
    }
}
