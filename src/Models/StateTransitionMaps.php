<?php

namespace Aegisora\Rules\StateTransition\Models;

class StateTransitionMaps
{
    /**
     * @var StateTransitionMap[]
     */
    private array $maps;

    /**
     * @param StateTransitionMap[] $maps
     */
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
     * @param mixed[] $rawData
     */
    public static function createFromArray(array $rawData): self
    {
        $maps = [];

        foreach ($rawData as $stateTransitionMapRawData) {
            if (!is_array($stateTransitionMapRawData)) {
                continue;
            }

            foreach ($stateTransitionMapRawData as $sourceStateName => $transitionStatesRawData) {
                if (!is_string($sourceStateName)) {
                    continue;
                }

                if (empty($sourceStateName)) {
                    continue;
                }

                $transitionStates = [];

                foreach ($transitionStatesRawData as $transitionStateRawData) {
                    if (!is_string($transitionStateRawData)) {
                        continue;
                    }

                    if (empty($transitionStateRawData)) {
                        continue;
                    }

                    $transitionStates[] = State::create($transitionStateRawData);
                }

                $maps[] = StateTransitionMap::create(State::create($sourceStateName), $transitionStates);
            }
        }

        return self::create($maps);
    }

    /**
     * @param StateTransitionMap[] $maps
     */
    public static function create(array $maps): self
    {
        return new self($maps);
    }

    /**
     * @return StateTransitionMap[]
     */
    public function getMaps(): array
    {
        return $this->maps;
    }
}
