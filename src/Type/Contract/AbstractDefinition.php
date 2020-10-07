<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

//@phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousAbstractClassNaming.SuperfluousPrefix
abstract class AbstractDefinition extends \Graphpinator\Type\Contract\NamedDefinition
    implements Outputable
{
    abstract public function isImplementedBy(\Graphpinator\Type\Contract\Definition $definition) : bool;
    
    public function createResolvableValue($rawValue) : \Graphpinator\Value\ResolvableValue
    {
        throw new \Graphpinator\Exception\Resolver\FieldResultAbstract();
    }
}
