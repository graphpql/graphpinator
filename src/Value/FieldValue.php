<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class FieldValue implements \JsonSerializable
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Typesystem\Field\Field $field,
        private \Graphpinator\Value\ResolvedValue $value,
        private \Graphpinator\Value\ResolvedValue $intermediateValue,
    )
    {
        foreach ($field->getDirectiveUsages() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof \Graphpinator\Typesystem\Location\FieldDefinitionLocation);
            $directiveDef->resolveFieldDefinitionValue($directive->getArgumentValues(), $this);
        }
    }

    public function jsonSerialize() : \Graphpinator\Value\ResolvedValue
    {
        return $this->value;
    }

    public function getField() : \Graphpinator\Typesystem\Field\Field
    {
        return $this->field;
    }

    public function getValue() : \Graphpinator\Value\ResolvedValue
    {
        return $this->value;
    }

    public function getIntermediateValue() : \Graphpinator\Value\ResolvedValue
    {
        return $this->intermediateValue;
    }
}
