<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

//@phpcs:ignore SlevomatCodingStandard.Classes.SuperfluousAbstractClassNaming.SuperfluousPrefix
abstract class AbstractDefinition extends \Graphpinator\Type\Contract\NamedDefinition implements \Graphpinator\Type\Contract\Outputable
{
    abstract public function isImplementedBy(\Graphpinator\Type\Contract\Definition $definition) : bool;
    
    abstract public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue;
}
