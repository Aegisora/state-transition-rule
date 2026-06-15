<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit\Models;

use Aegisora\Rules\StateTransition\Models\State;
use Aegisora\Rules\StateTransition\Models\StateTransitionMap;
use Aegisora\Rules\StateTransition\Models\StateTransitionMaps;
use PHPUnit\Framework\TestCase;
use stdClass;

class StateTransitionMapsTest extends TestCase
{
    /**
     * @dataProvider getCreateStateTransitionMapsProvidedData
     */
    public function testCreate(
        array $actualData,
        array $expectedData
    ): void {
        self::assertActualStateTransitionMapsEqualsExpected(
            new StateTransitionMaps($actualData),
            $expectedData
        );
        self::assertActualStateTransitionMapsEqualsExpected(
            StateTransitionMaps::create($actualData),
            $expectedData
        );
    }

    public static function getCreateStateTransitionMapsProvidedData(): array
    {
        return [
            'map - empty' => [
                'actualData' => [],
                'expectedData' => [
                    'maps' => [],
                ],
            ],
            'map - one state transition map' => [
                'actualData' => [
                    new StateTransitionMap(new State('foo'), [new State('bar'), new State('fooBar'),]),
                ],
                'expectedData' => [
                    'maps' => [
                        new StateTransitionMap(new State('foo'), [new State('bar'), new State('fooBar'),]),
                    ],
                ],
            ],
            'map - multiple state transition maps' => [
                'actualData' => [
                    new StateTransitionMap(new State('foo'), [new State('bar'), new State('fooBar'),]),
                    new StateTransitionMap(new State('foo'), []),
                    new StateTransitionMap(new State('foo'), [new State('bar'),]),
                ],
                'expectedData' => [
                    'maps' => [
                        new StateTransitionMap(new State('foo'), [new State('bar'), new State('fooBar'),]),
                        new StateTransitionMap(new State('foo'), []),
                        new StateTransitionMap(new State('foo'), [new State('bar'),]),
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getCreateFromArrayStateTransitionMapsProvidedData
     */
    public function testCreateFromArray(
        array $rawData,
        array $expectedData
    ): void {
        self::assertActualStateTransitionMapsEqualsExpected(
            StateTransitionMaps::createFromArray($rawData),
            $expectedData
        );
    }

    public static function getCreateFromArrayStateTransitionMapsProvidedData(): array
    {
        return [
            'raw data - empty' => [
                'rawData' => [],
                'expectedData' => [
                    'maps' => [],
                ],
            ],
            'raw data - valid array' => [
                'rawData' => [
                    ['StateA' => [],],
                    ['StateB' => ['StateD', 'StateC', 'StateE',],],
                    ['StateC' => ['StateD'],],
                ],
                'expectedData' => [
                    'maps' => [
                        new StateTransitionMap(new State('StateA'), []),
                        new StateTransitionMap(new State('StateB'), [new State('StateD'), new State('StateC'), new State('StateE'),]),
                        new StateTransitionMap(new State('StateC'), [new State('StateD'),]),
                    ],
                ],
            ],
            'raw data - skips non array top level elements' => [
                'rawData' => [
                    null,
                    123,
                    'invalid',
                    true,
                    new stdClass(),
                    ['StateA' => ['StateB']],
                ],
                'expectedData' => [
                    'maps' => [
                        new StateTransitionMap(new State('StateA'), [new State('StateB'),]),
                    ],
                ],
            ],
            'raw data - skips source states with non array transition states' => [
                'rawData' => [
                    ['StateA' => 'StateB'],
                    ['StateB' => null],
                    ['StateC' => 123],
                    ['StateD' => true],
                    ['StateE' => new stdClass()],
                    ['StateF' => ['StateG']],
                ],
                'expectedData' => [
                    'maps' => [
                        new StateTransitionMap(new State('StateF'), [new State('StateG'),]),
                    ],
                ],
            ],
            'raw data - multiple source states in one map array' => [
                'rawData' => [
                    [
                        'StateA' => ['StateB'],
                        'StateC' => ['StateD'],
                    ],
                ],
                'expectedData' => [
                    'maps' => [
                        new StateTransitionMap(new State('StateA'), [new State('StateB')]),
                        new StateTransitionMap(new State('StateC'), [new State('StateD')]),
                    ],
                ],
            ],
            'raw data - creates map with empty transitions after filtering invalid values' => [
                'rawData' => [
                    ['StateA' => [0, 1, true, false, '', new stdClass()]],
                ],
                'expectedData' => [
                    'maps' => [
                        new StateTransitionMap(new State('StateA'), []),
                    ],
                ],
            ],
            'raw data - with invalid elements' => [
                'rawData' => [
                    ['StateA' => [],],
                    ['StateB' => ['StateD', 'StateC', 'StateE',],],
                    ['StateB' => ['StateD', 'StateC', 'StateE',],],
                    [1 => ['StateD', 'StateC', 'StateE',],],
                    [0 => ['StateD', 'StateC', 'StateE',],],
                    [0.1 => ['StateD', 'StateC', 'StateE',],],
                    [true => ['StateD', 'StateC', 'StateE',],],
                    [false => ['StateD', 'StateC', 'StateE',],],
                    ['' => ['StateD', 'StateC', 'StateE',],],
                    ['StateC' => ['StateD'],],
                    ['StateD' => ['StateA', 0, 1, 'StateA', 0.1, 'StateB', true, 'StateC', false, '', new stdClass(), 'StateD', ''],],
                    ['StateD' => ['StateA', 0, 1, 'StateA', 0.1, 'StateB', true, 'StateC', false, '', new stdClass(), 'StateD', ''],],
                    ['StateE' => ['StateA', 0, 1, 'StateA', 0.1, 'StateB', true, 'StateC', false, '', new stdClass(), 'StateD', ''],],
                    ['0' => ['StateA', 0, 1, 'StateA', 0.1, 'StateB', true, 'StateC', false, '', new stdClass(), 'StateD', ''],],
                    ['0StateF' => ['StateA', 0, 1, 'StateA', 0.1, 'StateB', true, 'StateC', false, '', new stdClass(), 'StateD', ''],],
                    ['StateF0' => ['StateA', 0, 1, 'StateA', 0.1, 'StateB', true, 'StateC', false, '', new stdClass(), 'StateD', ''],],
                    ['0StateF0' => ['StateA', 0, 1, 'StateA', 0.1, 'StateB', true, 'StateC', false, '', new stdClass(), 'StateD', ''],],
                ],
                'expectedData' => [
                    'maps' => [
                        new StateTransitionMap(new State('StateA'), []),
                        new StateTransitionMap(new State('StateB'), [new State('StateD'), new State('StateC'), new State('StateE'),]),
                        new StateTransitionMap(new State('StateC'), [new State('StateD'),]),
                        new StateTransitionMap(new State('StateD'), [new State('StateA'), new State('StateB'), new State('StateC'), new State('StateD'),]),
                        new StateTransitionMap(new State('StateE'), [new State('StateA'), new State('StateB'), new State('StateC'), new State('StateD'),]),
                        new StateTransitionMap(new State('0StateF'), [new State('StateA'), new State('StateB'), new State('StateC'), new State('StateD'),]),
                        new StateTransitionMap(new State('StateF0'), [new State('StateA'), new State('StateB'), new State('StateC'), new State('StateD'),]),
                        new StateTransitionMap(new State('0StateF0'), [new State('StateA'), new State('StateB'), new State('StateC'), new State('StateD'),]),
                    ],
                ],
            ],
        ];
    }

    private static function assertActualStateTransitionMapsEqualsExpected(
        StateTransitionMaps $actual,
        array $expectedData
    ): void {
        self::assertEquals($expectedData['maps'], $actual->getMaps());
    }
}
