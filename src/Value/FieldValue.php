<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Location\FieldDefinitionLocation;

final class FieldValue implements \JsonSerializable
{
    public function __construct(
        private Field $field,
        private ResolvedValue $value,
    )
    {
        foreach ($field->getDirectiveUsages() as $directiveUsage) {
            $directive = $directiveUsage->getDirective();
            \assert($directive instanceof FieldDefinitionLocation);
            $directive->resolveFieldDefinitionValue($directiveUsage->getArgumentValues(), $this);
        }
    }

    #[\Override]
    public function jsonSerialize() : ResolvedValue
    {
        return $this->value;
    }

    public function getField() : Field
    {
        return $this->field;
    }

    public function getValue() : ResolvedValue
    {
        return $this->value;
    }
}
