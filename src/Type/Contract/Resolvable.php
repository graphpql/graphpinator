<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Contract;

interface Resolvable extends \Graphpinator\Type\Contract\Outputable
{
    //@phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
    public function resolve(?\Graphpinator\Normalizer\FieldSet $requestedFields, \Graphpinator\Resolver\FieldResult $parentResult);

    public function validateValue($rawValue) : void;
}
