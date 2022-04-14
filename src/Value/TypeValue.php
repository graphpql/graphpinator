<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class TypeValue implements \Graphpinator\Value\OutputValue
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Typesystem\Type $type,
        private \stdClass $value,
        private TypeIntermediateValue $intermediateValue,
    )
    {
        foreach ($type->getDirectiveUsages() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof \Graphpinator\Typesystem\Location\ObjectLocation);
            $directiveDef->resolveObject($directive->getArgumentValues(), $this);
        }
    }

    public function getRawValue() : \stdClass
    {
        return $this->value;
    }

    public function getType() : \Graphpinator\Typesystem\Type
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

    public function __get(string $name) : \Graphpinator\Value\FieldValue
    {
        return $this->value->{$name};
    }

    public function __isset(string $name) : bool
    {
        return \property_exists($this->value, $name);
    }
}
