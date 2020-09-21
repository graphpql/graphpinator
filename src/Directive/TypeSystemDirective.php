<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

abstract class TypeSystemDirective extends \Graphpinator\Directive\Directive
{
    public function getArguments() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([]);
    }
}
