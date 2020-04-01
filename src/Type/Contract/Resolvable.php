<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Resolvable extends \Graphpinator\Type\Contract\Outputable
{
    public function resolveFields(?\Graphpinator\Request\FieldSet $requestedFields, \Graphpinator\Field\ResolveResult $parent);

    public function validateValue($rawValue) : void;
}
