<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit\Models;

use Aegisora\Rules\StateTransition\Models\State;
use Aegisora\Rules\StateTransition\Models\StateTransitionMap;
use PHPUnit\Framework\TestCase;
use stdClass;

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
            'transition states - one State object' => [
                'actualData' => [
                    new State('foo'),
                    [
                        new State('bar'),
                    ],
                ],
                'expectedData' => [
                    'sourceState' => new State('foo'),
                    'transitionStates' => [
                        new State('bar'),
                    ],
                ],
            ],
            'transition states - multiple State objects' => [
                'actualData' => [
                    new State('foo'),
                    [
                        new State('bar1'),
                        new State('bar2'),
                        new State('bar3'),
                    ],
                ],
                'expectedData' => [
                    'sourceState' => new State('foo'),
                    'transitionStates' => [
                        new State('bar1'),
                        new State('bar2'),
                        new State('bar3'),
                    ],
                ],
            ],
            'transition states - one not State object' => [
                'actualData' => [
                    new State('foo'),
                    [
                        new stdClass(),
                    ],
                ],
                'expectedData' => [
                    'sourceState' => new State('foo'),
                    'transitionStates' => [],
                ],
            ],
            'transition states - multiple not State objects' => [
                'actualData' => [
                    new State('foo'),
                    [
                        new stdClass(),
                        new stdClass(),
                        new stdClass(),
                    ],
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
