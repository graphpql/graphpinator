<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\RefinerModule;

final class FieldForName
{
    use \Nette\SmartObject;

    public function __construct(
        public \Graphpinator\Normalizer\Selection\Field $field,
        public ?\Graphpinator\Typesystem\Contract\TypeConditionable $fragmentType,
    )
    {
    }
}
