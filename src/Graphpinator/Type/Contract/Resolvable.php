<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Type\Contract;

interface Resolvable extends \Infinityloop\Graphpinator\Type\Contract\Outputable
{
    public function resolveFields(?\Infinityloop\Graphpinator\Request\FieldSet $requestedFields, \Infinityloop\Graphpinator\Field\ResolveResult $parent);

    public function validateValue($rawValue) : void;
}
