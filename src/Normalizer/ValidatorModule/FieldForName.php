<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\ValidatorModule;

use Graphpinator\Normalizer\Selection\Field;
use Graphpinator\Typesystem\Contract\NamedType;

final readonly class FieldForName
{
    public function __construct(
        public Field $field,
        public ?NamedType $fragmentType,
    )
    {
    }
}
