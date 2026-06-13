<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit\Models;

use Aegisora\Rules\StateTransition\Models\State;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    /**
     * @dataProvider getCreateStateProvidedData
     */
    public function testCreate(
        array $actualData,
        array $expectedData
    ): void {
        self::assertActualStateEqualsExpected(new State(...array_values($actualData)), $expectedData);
        self::assertActualStateEqualsExpected(State::create(...array_values($actualData)), $expectedData);
    }

    public static function getCreateStateProvidedData(): array
    {
        return [
            'state name - not empty' => [
                'actualData' => [
                    'name' => 'foo',
                ],
                'expectedData' => [
                    'name' => 'foo',
                ],
            ],
            'state name - empty' => [
                'actualData' => [
                    'name' => '',
                ],
                'expectedData' => [
                    'name' => '',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getStateEqualityCheckProvidedDate
     */
    public function testIsEqual(
        array $sourceStateData,
        array $targetStateData,
        bool $isEqual
    ): void {
        self::assertSame(
            $isEqual,
            State::create(...array_values($sourceStateData))->isEqual(State::create(...array_values($targetStateData)))
        );
    }

    public static function getStateEqualityCheckProvidedDate(): array
    {
        return [
            'source state name - empty, target state name - empty' => [
                'sourceStateData' => [
                    'name' => '',
                ],
                'targetStateData' => [
                    'name' => '',
                ],
                'isEqual' => true,
            ],
            'source state name - not empty, target state name - empty' => [
                'sourceStateData' => [
                    'name' => 'foo',
                ],
                'targetStateData' => [
                    'name' => '',
                ],
                'isEqual' => false,
            ],
            'source state name - empty, target state name - not empty' => [
                'sourceStateData' => [
                    'name' => '',
                ],
                'targetStateData' => [
                    'name' => 'foo',
                ],
                'isEqual' => false,
            ],
            'source state name - not empty, target state name - not empty and equal' => [
                'sourceStateData' => [
                    'name' => 'foo',
                ],
                'targetStateData' => [
                    'name' => 'foo',
                ],
                'isEqual' => true,
            ],
        ];
    }

    private static function assertActualStateEqualsExpected(
        State $actual,
        array $expectedData
    ): void {
        self::assertSame($expectedData['name'], $actual->getName());
    }
}
