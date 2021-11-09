<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use \Graphpinator\Normalizer\RefinerModule\DuplicateFieldModule;
use \Graphpinator\Normalizer\RefinerModule\DuplicateFragmentSpreadModule;
use \Graphpinator\Normalizer\RefinerModule\EmptyFragmentModule;
use \Graphpinator\Normalizer\Selection\SelectionSet;

final class SelectionSetRefiner
{
    use \Nette\SmartObject;

    public function __construct(
        private SelectionSet $selections,
    )
    {
    }

    public function refine() : void
    {
        $modules = [
            new DuplicateFragmentSpreadModule($this->selections),
            new DuplicateFieldModule($this->selections),
            new EmptyFragmentModule($this->selections),
        ];

        foreach ($modules as $module) {
            $module->refine();
        }
    }
}
