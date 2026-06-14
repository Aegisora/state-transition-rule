<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit\Models;

use Aegisora\Rules\StateTransition\Models\State;
use Aegisora\Rules\StateTransition\Models\StateTransitionMap;
use PHPUnit\Framework\TestCase;

class StateTransitionMapTest extends TestCase
{
    /**
     * @dataProvider getCreateStateTransitionMapProvidedData
     */
    public function testCreate(
        array $actualData,
        array $expectedData
    ): void {
        self::assertActualStateTransitionMapEqualsExpected(
            new StateTransitionMap(...array_values($actualData)),
            $expectedData
        );
        self::assertActualStateTransitionMapEqualsExpected(
            StateTransitionMap::create(...array_values($actualData)),
            $expectedData
        );
    }

    public static function getCreateStateTransitionMapProvidedData(): array
    {
        return [
            'transition states - empty' => [
                'actualData' => [
                    new State('foo'),
                    [],
                ],
                'expectedData' => [
                    'sourceState' => new State('foo'),
                    'transitionStates' => [],
                ],
            ],
        ];
    }

    private static function assertActualStateTransitionMapEqualsExpected(
        StateTransitionMap $actual,
        array $expectedData
    ): void {
        self::assertEquals($expectedData['sourceState'], $actual->getSourceState());
        self::assertEquals($expectedData['transitionStates'], $actual->getTransitionStates());
    }
}
