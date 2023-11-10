<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class FieldValue implements \JsonSerializable
{
    public function __construct(
        private \Graphpinator\Typesystem\Field\Field $field,
        private \Graphpinator\Value\ResolvedValue $value,
    )
    {
        foreach ($field->getDirectiveUsages() as $directiveUsage) {
            $directive = $directiveUsage->getDirective();
            \assert($directive instanceof \Graphpinator\Typesystem\Location\FieldDefinitionLocation);
            $directive->resolveFieldDefinitionValue($directiveUsage->getArgumentValues(), $this);
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
}
