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

    private static function assertActualStateEqualsExpected(
        State $actual,
        array $expectedData
    ): void {
        self::assertSame($expectedData['name'], $actual->getName());
    }
}
