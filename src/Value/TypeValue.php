<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use \Graphpinator\Typesystem\Location\ObjectLocation;

final class TypeValue implements \Graphpinator\Value\OutputValue
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Typesystem\Type $type,
        private \stdClass $value,
    )
    {
        foreach ($type->getDirectiveUsages() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof ObjectLocation);
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
