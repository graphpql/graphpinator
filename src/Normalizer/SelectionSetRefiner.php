<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class SelectionSetRefiner
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Normalizer\Selection\SelectionSet $selections,
    )
    {
    }

    public function refine() : void
    {
        $modules = [
            new \Graphpinator\Normalizer\RefinerModule\DuplicateFragmentSpreadModule($this->selections),
            new \Graphpinator\Normalizer\RefinerModule\DuplicateFieldModule($this->selections),
            new \Graphpinator\Normalizer\RefinerModule\EmptyFragmentModule($this->selections),
        ];

        foreach ($modules as $module) {
            $module->refine();
        }
    }
}
