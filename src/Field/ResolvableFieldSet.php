<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

/**
 * @method \Graphpinator\Field\ResolvableField current() : object
 * @method \Graphpinator\Field\ResolvableField offsetGet($offset) : object
 */
final class ResolvableFieldSet extends \Graphpinator\Field\FieldSet
{
    protected const INNER_CLASS = ResolvableField::class;
}
