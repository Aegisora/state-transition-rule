<?php

namespace Aegisora\Rules\StateTransition\Models;

class StateTransition
{
    private State $from;
    private State $to;

    public function __construct(
        State $from,
        State $to
    ) {
        $this->from = $from;
        $this->to = $to;
    }
}
