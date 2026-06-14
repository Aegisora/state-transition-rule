<?php

namespace Aegisora\Rules\StateTransition\Models;

class StateTransitionMap
{
    private State $sourceState;

    /** @var State[] */
    private array $transitionStates = [];

    /**
     * @param State[] $transitionStates
     */
    public function __construct(
        State $sourceState,
        array $transitionStates
    ) {
        $this->sourceState = $sourceState;
        $this->transitionStates = array_filter(
            $transitionStates,
            static function ($transitionState): bool {
                return $transitionState instanceof State;
            }
        );
    }
}
