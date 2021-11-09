<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\ValidatorModule;

use \Graphpinator\Typesystem\Contract\TypeConditionable;

final class FieldForName
{
    use \Nette\SmartObject;

    public function __construct(
        public \Graphpinator\Normalizer\Selection\Field $field,
        public ?TypeConditionable $fragmentType,
    )
    {
    }
}
