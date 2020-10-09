<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Resolvable extends \Graphpinator\Type\Contract\Outputable
{
    public function resolve(
        ?\Graphpinator\Normalizer\FieldSet $requestedFields,
        \Graphpinator\Value\ResolvedValue $parentResult
    ) : \Graphpinator\Value\ResolvedValue;
}
