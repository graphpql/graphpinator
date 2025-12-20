<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Refiner;

use Graphpinator\Normalizer\Refiner\Module\DuplicateFieldModule;
use Graphpinator\Normalizer\Refiner\Module\DuplicateFragmentSpreadModule;
use Graphpinator\Normalizer\Refiner\Module\EmptyFragmentModule;
use Graphpinator\Normalizer\Selection\SelectionSet;

final class SelectionSetRefiner
{
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
