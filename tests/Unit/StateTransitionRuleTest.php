<?php

namespace Aegisora\Rules\StateTransition\Tests\Unit;

use Aegisora\RuleContract\Exceptions\InvalidRuleContextException;
use Aegisora\RuleContract\Models\Context;
use Aegisora\RuleContract\Models\Result;
use Aegisora\RuleContract\RuleInterface;
use Aegisora\Rules\StateTransition\Models\State;
use Aegisora\Rules\StateTransition\Models\StateTransition;
use Aegisora\Rules\StateTransition\Models\StateTransitionMap;
use Aegisora\Rules\StateTransition\Models\StateTransitionMaps;
use Aegisora\Rules\StateTransition\StateTransitionRule;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;

class StateTransitionRuleTest extends TestCase
{
    public function testCreate(): void
    {
        self::assertInstanceOf(RuleInterface::class, StateTransitionRule::create(StateTransitionMaps::create([])));
    }

    /**
     * @dataProvider getValidateProvidedData
     */
    public function testValidate(
        $contextValue,
        StateTransitionMaps $allowedTransitions,
        array $expectedResultData
    ): void {
        self::assertActualResultEqualsExpected(
            StateTransitionRule::create($allowedTransitions)->validate($this->getContextMock(['value' => $contextValue,])),
            $expectedResultData
        );
    }

    public static function getValidateProvidedData(): array
    {
        return [
            'allowed transition maps - empty' => [
                'contextValue' => StateTransition::create(State::create('StateA'), State::create('StateB')),
                'allowedTransitions' => StateTransitionMaps::create([]),
                'expectedResultData' => [
                    'isValid' => false,
                    'failedRuleCode' => 'state_transition_rule',
                ],
            ],
            'allowed transition maps - source state not exists' => [
                'contextValue' => StateTransition::create(State::create('StateA'), State::create('StateB')),
                'allowedTransitions' => StateTransitionMaps::create([
                    StateTransitionMap::create(State::create('StateB'), []),
                    StateTransitionMap::create(State::create('StateC'), []),
                    StateTransitionMap::create(State::create('StateD'), []),
                ]),
                'expectedResultData' => [
                    'isValid' => false,
                    'failedRuleCode' => 'state_transition_rule',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getInvalidContextProvidedData
     */
    public function testValidateFailedCauseInvalidContext(
        $contextValue
    ): void {
        $this->expectException(InvalidRuleContextException::class);

        StateTransitionRule::create(StateTransitionMaps::create([]))->validate($this->getContextMock(['value' => $contextValue,]));
    }

    public static function getInvalidContextProvidedData(): array
    {
        return [
            'value - null' => [
                'value' => null,
            ],
            'value - not empty string' => [
                'value' => 'foo',
            ],
            'value - empty string' => [
                'value' => '',
            ],
            'value - zero integer' => [
                'value' => 0,
            ],
            'value - positive integer' => [
                'value' => 1,
            ],
            'value - negative integer' => [
                'value' => -1,
            ],
            'value - zero float' => [
                'value' => 0.0,
            ],
            'value - positive float' => [
                'value' => 0.1,
            ],
            'value - negative float' => [
                'value' => -0.1,
            ],
            'value - boolean true' => [
                'value' => true,
            ],
            'value - boolean false' => [
                'value' => false,
            ],
            'value - empty array' => [
                'value' => [],
            ],
            'value - not empty array' => [
                'value' => [1,],
            ],
            'value - object' => [
                'value' => new stdClass(),
            ],
            'value - callable' => [
                'value' => static function () {
                },
            ],
            'value - resource' => [
                'value' => tmpfile(),
            ],
        ];
    }

    private static function assertActualResultEqualsExpected(
        Result $actual,
        array $expected
    ): void {
        self::assertSame($expected['isValid'], $actual->isValid());
        self::assertSame($expected['failedRuleCode'], $actual->getFailedRuleCode());
    }

    /**
     * @return Context|MockObject
     */
    private function getContextMock(array $params = []): Context
    {
        /** @var Context|MockObject $mock */
        $mock = $this->createMock(Context::class);
        $mock->method('getValue')->willReturn($params['value'] ?? null);

        return $mock;
    }
}
