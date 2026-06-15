<?php

namespace Aegisora\Rules\StateTransition\Models;

class StateTransitionMaps
{
    /**
     * @var StateTransitionMap[]
     */
    private array $maps;

    public function __construct(
        array $maps
    ) {
        $this->maps = array_values(
            array_filter(
                $maps,
                static function ($stateTransitionMap): bool {
                    return $stateTransitionMap instanceof StateTransitionMap;
                }
            )
        );
    }

    /**
     * @return StateTransitionMap[]
     */
    public function getMaps(): array
    {
        return $this->maps;
    }
}
