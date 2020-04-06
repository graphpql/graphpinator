<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Resolvable extends \Graphpinator\Type\Contract\Outputable, \Graphpinator\Type\Contract\Instantiable
{
    public function resolveFields(?\Graphpinator\Request\FieldSet $requestedFields, \Graphpinator\Request\ResolveResult $parentResult);

    public function validateValue($rawValue) : void;
}
