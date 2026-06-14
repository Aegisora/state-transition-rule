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

    /**
     * @param State[] $transitionStates
     */
    public static function create(
        State $sourceState,
        array $transitionStates
    ): self {
        return new self($sourceState, $transitionStates);
    }

    public function getSourceState(): State
    {
        return $this->sourceState;
    }

    /**
     * @return State[]
     */
    public function getTransitionStates(): array
    {
        return $this->transitionStates;
    }
}
