<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

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
            new \Graphpinator\Normalizer\ValidatorModule\ValidateFieldsCanMergeModule($this->selections),
        ];

        foreach ($modules as $module) {
            $module->validate();
        }
    }
}
