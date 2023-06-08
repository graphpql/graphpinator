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
        foreach ($type->getDirectiveUsages() as $directiveUsage) {
            $directive = $directiveUsage->getDirective();
            \assert($directive instanceof \Graphpinator\Typesystem\Location\ObjectLocation);
            $directive->resolveObject($directiveUsage->getArgumentValues(), $this);
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
