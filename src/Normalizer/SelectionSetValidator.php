<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\ValidatorModule\ValidateFieldsCanMergeModule;

final class SelectionSetValidator
{
    public function __construct(
        private SelectionSet $selections,
    )
    {
    }

    public function validate() : void
    {
        $modules = [
            new ValidateFieldsCanMergeModule($this->selections),
        ];

        foreach ($modules as $module) {
            $module->validate();
        }
    }
}
