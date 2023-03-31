<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Field;

/**
 * @method ResolvableField current() : object
 * @method ResolvableField offsetGet($offset) : object
 */
final class ResolvableFieldSet extends FieldSet
{
    protected const INNER_CLASS = ResolvableField::class;
}
