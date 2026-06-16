<?php

namespace Aegisora\Rules\StateTransition;

use Aegisora\Rules\StateTransition\Models\StateTransitionMaps;

class StateTransitionRule
{
    private StateTransitionMaps $allowedTransitions;

    public function __construct(
        StateTransitionMaps $allowedTransitions
    ) {
        $this->allowedTransitions = $allowedTransitions;
    }
}
