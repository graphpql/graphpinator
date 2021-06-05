<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Field;

/**
 * @method \Graphpinator\Typesystem\Field\ResolvableField current() : object
 * @method \Graphpinator\Typesystem\Field\ResolvableField offsetGet($offset) : object
 */
final class ResolvableFieldSet extends \Graphpinator\Typesystem\Field\FieldSet
{
    protected const INNER_CLASS = ResolvableField::class;
}
