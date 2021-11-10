<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

use \Graphpinator\Normalizer\ValidatorModule\ValidateFieldsCanMergeModule;

final class SelectionSetValidator
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Normalizer\Selection\SelectionSet $selections,
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
