<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit\Models;

use Aegisora\Rules\StateTransition\Models\State;
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
        self::assertActualStateTransitionEqualsExpected(
            new StateTransition(
                State::create(...array_values($actualData['from'])),
                State::create(...array_values($actualData['to']))
            ),
            $expectedData
        );
        self::assertActualStateTransitionEqualsExpected(
            StateTransition::create(
                State::create(...array_values($actualData['from'])),
                State::create(...array_values($actualData['to']))
            ),
            $expectedData
        );
    }

    public static function getCreateStateTransitionProvidedData(): array
    {
        return [
            'state from name - not empty, state to name - not empty' => [
                'actualData' => [
                    'from' => [
                        'name' => 'foo',
                    ],
                    'to' => [
                        'name' => 'bar',
                    ],
                ],
                'expectedData' => [
                    'from' => [
                        'name' => 'foo',
                    ],
                    'to' => [
                        'name' => 'bar',
                    ],
                ],
            ],
            'state from name - empty, state to name - empty' => [
                'actualData' => [
                    'from' => [
                        'name' => '',
                    ],
                    'to' => [
                        'name' => '',
                    ],
                ],
                'expectedData' => [
                    'from' => [
                        'name' => '',
                    ],
                    'to' => [
                        'name' => '',
                    ],
                ],
            ],
        ];
    }

    private static function assertActualStateTransitionEqualsExpected(
        StateTransition $actual,
        array $expectedData
    ): void {
        self::assertSame($expectedData['from']['name'], $actual->getFrom()->getName());
        self::assertSame($expectedData['to']['name'], $actual->getTo()->getName());
    }
}
