<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use \Graphpinator\Typesystem\Location\FieldDefinitionLocation;

final class FieldValue implements \JsonSerializable
{
    use \Nette\SmartObject;

    public function __construct(
        private \Graphpinator\Typesystem\Field\Field $field,
        private ResolvedValue $value,
        private ResolvedValue $intermediateValue,
    )
    {
        foreach ($field->getDirectiveUsages() as $directive) {
            $directiveDef = $directive->getDirective();
            \assert($directiveDef instanceof FieldDefinitionLocation);
            $directiveDef->resolveFieldDefinitionValue($directive->getArgumentValues(), $this);
        }
    }

    public function jsonSerialize() : ResolvedValue
    {
        return $this->value;
    }

    public function getField() : \Graphpinator\Typesystem\Field\Field
    {
        return $this->field;
    }

    public function getValue() : ResolvedValue
    {
        return $this->value;
    }

    public function getIntermediateValue() : ResolvedValue
    {
        return $this->intermediateValue;
    }
}
