<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

abstract class AbstractDefinition extends \Graphpinator\Type\Contract\NamedDefinition
{
    abstract public function isImplementedBy(\Graphpinator\Type\Contract\Definition $definition) : bool;
    
    public function createValue($rawValue) : \Graphpinator\Resolver\Value\TypeValue
    {
        throw new \Graphpinator\Exception\Resolver\FieldResultAbstract();
    }
}
