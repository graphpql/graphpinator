<?php

declare(strict_types = 1);

namespace PGQL\Type\Contract;

interface Resolvable extends \PGQL\Type\Contract\Outputable
{
    public function resolveFields(?\PGQL\Parser\RequestFieldSet $requestedFields, \PGQL\Field\ResolveResult $parent);

    public function validateValue($rawValue) : void;
}
