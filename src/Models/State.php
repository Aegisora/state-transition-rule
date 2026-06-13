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

    public function isEqual(State $state): bool
    {
        return $this->getName() === $state->getName();
    }

    public function getName(): string
    {
        return $this->name;
    }
}
