<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\Location\ObjectLocation;
use Graphpinator\Typesystem\Type;

final class TypeValue implements OutputValue
{
    public function __construct(
        private Type $type,
        private \stdClass $value,
        private TypeIntermediateValue $intermediateValue,
    )
    {
        foreach ($type->getDirectiveUsages() as $directiveUsage) {
            $directive = $directiveUsage->getDirective();
            \assert($directive instanceof ObjectLocation);
            $directive->resolveObject($directiveUsage->getArgumentValues(), $this);
        }
    }

    public function getRawValue() : \stdClass
    {
        return $this->value;
    }

    public function getType() : Type
    {
        return $this->type;
    }

    public function getIntermediateValue() : TypeIntermediateValue
    {
        return $this->intermediateValue;
    }

    public function jsonSerialize() : \stdClass
    {
        return $this->value;
    }

    public function __get(string $name) : FieldValue
    {
        return $this->value->{$name};
    }

    public function __isset(string $name) : bool
    {
        return \property_exists($this->value, $name);
    }
}
