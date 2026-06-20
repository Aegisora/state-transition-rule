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

    public static function create(
        State $from,
        State $to
    ): self {
        return new self($from, $to);
    }

    public function getFrom(): State
    {
        return $this->from;
    }

    public function getTo(): State
    {
        return $this->to;
    }
}
