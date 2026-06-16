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
        Context $context,
        StateTransitionMaps $allowedTransitions,
        array $expectedResultData
    ): void {
        self::assertActualResultEqualsExpected(
            StateTransitionRule::create($allowedTransitions)->validate($context),
            $expectedResultData
        );
    }

    public static function getValidateProvidedData(): array
    {
        return [
            'allowed transition maps - empty' => [
                'context' => Context::create(StateTransition::create(State::create('StateA'), State::create('StateB'))),
                'allowedTransitions' => StateTransitionMaps::create([]),
                'expectedResultData' => [
                    'isValid' => false,
                    'failedRuleCode' => 'state_transition_rule',
                ],
            ],
            'allowed transition maps - source state not exists' => [
                'context' => Context::create(StateTransition::create(State::create('StateA'), State::create('StateB'))),
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
            'allowed transition maps - source state exists, allowed transition states - empty' => [
                'context' => Context::create(StateTransition::create(State::create('StateA'), State::create('StateB'))),
                'allowedTransitions' => StateTransitionMaps::create([
                    StateTransitionMap::create(State::create('StateB'), []),
                    StateTransitionMap::create(State::create('StateC'), []),
                    StateTransitionMap::create(State::create('StateA'), []),
                    StateTransitionMap::create(State::create('StateD'), []),
                ]),
                'expectedResultData' => [
                    'isValid' => false,
                    'failedRuleCode' => 'state_transition_rule',
                ],
            ],
            'allowed transition maps - source state exists, allowed transition states - target state not exists' => [
                'context' => Context::create(StateTransition::create(State::create('StateA'), State::create('StateB'))),
                'allowedTransitions' => StateTransitionMaps::create([
                    StateTransitionMap::create(State::create('StateB'), []),
                    StateTransitionMap::create(State::create('StateC'), []),
                    StateTransitionMap::create(State::create('StateA'), [State::create('StateD'),]),
                    StateTransitionMap::create(State::create('StateD'), []),
                ]),
                'expectedResultData' => [
                    'isValid' => false,
                    'failedRuleCode' => 'state_transition_rule',
                ],
            ],
            'allowed transition maps - first source state map wins when first is invalid' => [
                'context' => Context::create(StateTransition::create(State::create('StateA'), State::create('StateB'))),
                'allowedTransitions' => StateTransitionMaps::create([
                    StateTransitionMap::create(State::create('StateA'), [State::create('StateC')]),
                    StateTransitionMap::create(State::create('StateA'), [State::create('StateB')]),
                ]),
                'expectedResultData' => [
                    'isValid' => false,
                    'failedRuleCode' => 'state_transition_rule',
                ],
            ],
            'allowed transition maps - source state exists, allowed transition states - target state exists' => [
                'context' => Context::create(StateTransition::create(State::create('StateA'), State::create('StateB'))),
                'allowedTransitions' => StateTransitionMaps::create([
                    StateTransitionMap::create(State::create('StateB'), []),
                    StateTransitionMap::create(State::create('StateC'), []),
                    StateTransitionMap::create(State::create('StateA'), [State::create('StateB'),]),
                    StateTransitionMap::create(State::create('StateD'), []),
                ]),
                'expectedResultData' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
            'allowed transition maps - target state exists after non matching states' => [
                'context' => Context::create(StateTransition::create(State::create('StateA'), State::create('StateB'))),
                'allowedTransitions' => StateTransitionMaps::create([
                    StateTransitionMap::create(
                        State::create('StateA'),
                        [State::create('StateB'), State::create('StateC'), State::create('StateD'),]
                    ),
                ]),
                'expectedResultData' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
            'allowed transition maps - first source state map wins when first is valid' => [
                'context' => Context::create(StateTransition::create(State::create('StateA'), State::create('StateB'))),
                'allowedTransitions' => StateTransitionMaps::create([
                    StateTransitionMap::create(State::create('StateA'), [State::create('StateB')]),
                    StateTransitionMap::create(State::create('StateA'), [State::create('StateC')]),
                ]),
                'expectedResultData' => [
                    'isValid' => true,
                    'failedRuleCode' => null,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getInvalidContextProvidedData
     */
    public function testValidateFailedCauseInvalidContext(Context $context): void
    {
        $this->expectException(InvalidRuleContextException::class);

        StateTransitionRule::create(StateTransitionMaps::create([]))->validate($context);
    }

    public static function getInvalidContextProvidedData(): array
    {
        return [
            'value - null' => [
                'value' => Context::create(null),
            ],
            'value - not empty string' => [
                'value' => Context::create('foo'),
            ],
            'value - empty string' => [
                'value' => Context::create(''),
            ],
            'value - zero integer' => [
                'value' => Context::create(0),
            ],
            'value - positive integer' => [
                'value' => Context::create(1),
            ],
            'value - negative integer' => [
                'value' => Context::create(-1),
            ],
            'value - zero float' => [
                'value' => Context::create(0.0),
            ],
            'value - positive float' => [
                'value' => Context::create(0.1),
            ],
            'value - negative float' => [
                'value' => Context::create(-0.1),
            ],
            'value - boolean true' => [
                'value' => Context::create(true),
            ],
            'value - boolean false' => [
                'value' => Context::create(false),
            ],
            'value - empty array' => [
                'value' => Context::create([]),
            ],
            'value - not empty array' => [
                'value' => Context::create([1,]),
            ],
            'value - object' => [
                'value' => Context::create(new stdClass()),
            ],
            'value - callable' => [
                'value' => Context::create(
                    static function () {
                    }
                ),
            ],
            'value - resource' => [
                'value' => Context::create(tmpfile()),
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
}
