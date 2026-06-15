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
        /** @var StateTransitionMap[] $maps */
        $maps = [];

        /**
         * @var array<string, bool> $usedSourceStateNames
         */
        $usedSourceStateNames = [];

        foreach ($rawData as $stateTransitionMapRawData) {
            if (!is_array($stateTransitionMapRawData)) {
                continue;
            }

            foreach ($stateTransitionMapRawData as $sourceStateName => $transitionStatesRawData) {
                if (!is_string($sourceStateName)) {
                    continue;
                }

                if ($sourceStateName === '') {
                    continue;
                }

                if (isset($usedSourceStateNames[$sourceStateName])) {
                    continue;
                }

                if (!is_array($transitionStatesRawData)) {
                    continue;
                }

                /** @var State[] $transitionStates */
                $transitionStates = [];

                /**
                 * @var array<string, bool> $usedTransitionStateNames
                 */
                $usedTransitionStateNames = [];

                foreach ($transitionStatesRawData as $transitionStateRawData) {
                    if (!is_string($transitionStateRawData)) {
                        continue;
                    }

                    if ($transitionStateRawData === '') {
                        continue;
                    }

                    if (isset($usedTransitionStateNames[$transitionStateRawData])) {
                        continue;
                    }

                    $transitionStates[] = State::create($transitionStateRawData);
                    $usedTransitionStateNames[$transitionStateRawData] = true;
                }

                $maps[] = StateTransitionMap::create(
                    State::create($sourceStateName),
                    $transitionStates
                );

                $usedSourceStateNames[$sourceStateName] = true;
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
