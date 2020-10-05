<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Scalar;

abstract class ScalarType extends \Graphpinator\Type\Contract\LeafDefinition
{
    final public function getTypeKind() : string
    {
        return \Graphpinator\Type\Introspection\TypeKind::SCALAR;
    }

    final public function printSchema() : string
    {
        return $this->printDescription()
            . 'scalar ' . $this->getName();
    }
}
