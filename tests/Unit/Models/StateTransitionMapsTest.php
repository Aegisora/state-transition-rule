<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit\Models;

use Aegisora\Rules\StateTransition\Models\State;
use Aegisora\Rules\StateTransition\Models\StateTransitionMap;
use Aegisora\Rules\StateTransition\Models\StateTransitionMaps;
use PHPUnit\Framework\TestCase;

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
                    new StateTransitionMap(new State('foo'), [new State('bar'), ]),
                ],
                'expectedData' => [
                    'maps' => [
                        new StateTransitionMap(new State('foo'), [new State('bar'), new State('fooBar'),]),
                        new StateTransitionMap(new State('foo'), []),
                        new StateTransitionMap(new State('foo'), [new State('bar'), ]),
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
        ];
    }

    private static function assertActualStateTransitionMapsEqualsExpected(
        StateTransitionMaps $actual,
        array $expectedData
    ): void {
        self::assertEquals($expectedData['maps'], $actual->getMaps());
    }
}
