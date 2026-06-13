<?php

namespace Aegisora\Rules\StateTransition\Models;

class State
{
    private string $name;

    public function __construct(
        string $name
    ) {
        $this->name = $name;
    }
}
