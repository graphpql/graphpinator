<?php

declare(strict_types = 1);

namespace Graphpinator\Utils;

trait TTypeSystemElement
{
    final protected function printDescription() : string
    {
        if ($this->getDescription() === null) {
            return '';
        }

        return '"""' . \PHP_EOL . $this->getDescription() . \PHP_EOL . '"""' . \PHP_EOL;
    }
}
