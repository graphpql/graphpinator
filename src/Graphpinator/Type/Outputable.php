<?php

declare(strict_types = 1);

namespace PGQL\Type;

interface Outputable extends Definition
{
    public function resolveFields(?array $requestedFields, \PGQL\Field\ResolveResult $parentValue);
}
