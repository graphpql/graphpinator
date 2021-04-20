<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class SelectionSetRefiner
{
    use \Nette\SmartObject;

    private array $fieldsForName = [];
    private array $visitedFragments = [];
    private \SplStack $scopeStack;
    private array $modules;

    public function __construct(
        private \Graphpinator\Normalizer\Selection\SelectionSet $selections,
        private \Graphpinator\Type\Contract\Outputable $scope,
    )
    {
        $this->modules = [
            new \Graphpinator\Normalizer\RefinerModule\DuplicateFragmentSpreadModule($this->selections),
            new \Graphpinator\Normalizer\RefinerModule\DuplicateFieldModule($this->selections),
            new \Graphpinator\Normalizer\RefinerModule\EmptyFragmentModule($this->selections),
        ];
    }

    public function refine() : \Graphpinator\Normalizer\Selection\SelectionSet
    {
        foreach ($this->modules as $module) {
            $module->refine();
        }

        return $this->selections;
    }
}
