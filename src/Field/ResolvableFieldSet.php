<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

/**
 * @method ResolvableField current() : object
 * @method ResolvableField offsetGet($offset) : object
 */
final class ResolvableFieldSet extends \Graphpinator\Field\FieldSet
{
    protected const INNER_CLASS = ResolvableField::class;
}
