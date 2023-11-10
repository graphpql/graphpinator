<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\ValidatorModule;

final class FieldForName
{
    public function __construct(
        public \Graphpinator\Normalizer\Selection\Field $field,
        public ?\Graphpinator\Typesystem\Contract\TypeConditionable $fragmentType,
    )
    {
    }
}
