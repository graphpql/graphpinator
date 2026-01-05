<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\Location\ObjectLocation;
use Graphpinator\Typesystem\Type;
use Graphpinator\Value\Contract\OutputValue;

final readonly class TypeValue implements OutputValue
{
    public function __construct(
        public Type $type,
        /** @var \stdClass{...FieldValue} */
        public \stdClass $value,
        public TypeIntermediateValue $intermediateValue,
    )
    {
        foreach ($type->getDirectiveUsages() as $directiveUsage) {
            $directive = $directiveUsage->getDirective();
            \assert($directive instanceof ObjectLocation);
            $directive->resolveObject($directiveUsage->getArgumentValues(), $this);
        }
    }

    #[\Override]
    public function getRawValue() : \stdClass
    {
        return $this->value;
    }

    #[\Override]
    public function getType() : Type
    {
        return $this->type;
    }

    #[\Override]
    public function jsonSerialize() : \stdClass
    {
        return $this->value;
    }
}
