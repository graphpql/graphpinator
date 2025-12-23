<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Location\FieldDefinitionLocation;
use Graphpinator\Value\Contract\OutputValue;

final readonly class FieldValue implements \JsonSerializable
{
    public function __construct(
        public Field $field,
        public OutputValue $value,
    )
    {
        foreach ($field->getDirectiveUsages() as $directiveUsage) {
            $directive = $directiveUsage->getDirective();
            \assert($directive instanceof FieldDefinitionLocation);
            $directive->resolveFieldDefinitionValue($directiveUsage->getArgumentValues(), $this);
        }
    }

    #[\Override]
    public function jsonSerialize() : OutputValue
    {
        return $this->value;
    }
}
