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

    public static function create(
        string $name
    ): self {
        return new self($name);
    }

    public function getName(): string
    {
        return $this->name;
    }
}
