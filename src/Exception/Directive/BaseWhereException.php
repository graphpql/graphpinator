<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Directive;

abstract class BaseWhereException extends \Graphpinator\Exception\GraphpinatorBase
{
    protected function isOutputable() : bool
    {
        return true;
    }
    
    protected function printType(\Graphpinator\Value\ResolvedValue $resolvedValue) : string
    {
        if ($resolvedValue->getRawValue() === null) {
            return 'Null';
        }

        return $resolvedValue->getType()->printName();
    }
}
