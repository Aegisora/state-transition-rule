<?php

namespace Aegisora\Rules\StateTransition;

use Aegisora\RuleContract\Exceptions\InvalidRuleContextException;
use Aegisora\RuleContract\Models\Context;
use Aegisora\RuleContract\Models\Result;
use Aegisora\RuleContract\Rule;
use Aegisora\Rules\StateTransition\Models\State;
use Aegisora\Rules\StateTransition\Models\StateTransition;
use Aegisora\Rules\StateTransition\Models\StateTransitionMap;
use Aegisora\Rules\StateTransition\Models\StateTransitionMaps;

class StateTransitionRule extends Rule
{
    private StateTransitionMaps $allowedTransitions;

    public function __construct(
        StateTransitionMaps $allowedTransitions
    ) {
        $this->allowedTransitions = $allowedTransitions;
    }

    public static function create(
        StateTransitionMaps $allowedTransitions
    ): self {
        return new self($allowedTransitions);
    }

    protected function executeValidate(Context $context): Result
    {
        $stateTransition = $context->getValue();

        if (!$stateTransition instanceof StateTransition) {
            throw new InvalidRuleContextException();
        }

        $fromStateTransitionMap = $this->getSourceStateTransitionMap($stateTransition->getFrom());

        if (!$fromStateTransitionMap instanceof StateTransitionMap) {
            return $this->getDefaultInvalidResult();
        }

        // TODO: Implement executeValidate() method.
    }

    private function getSourceStateTransitionMap(State $from): ?StateTransitionMap
    {
        foreach ($this->allowedTransitions->getMaps() as $stateTransitionMap) {
            if ($stateTransitionMap->getSourceState()->isEqual($from)) {
                return $stateTransitionMap;
            }
        }

        return null;
    }

    /**
     * @param State[] $allowedTransitionStates
     */
    private function isTransitionAllowed(
        State $to,
        array $allowedTransitionStates
    ): bool {
        foreach ($allowedTransitionStates as $allowedTransitionState) {
            if ($allowedTransitionState->isEqual($to)) {
                return true;
            }
        }

        return false;
    }
}
