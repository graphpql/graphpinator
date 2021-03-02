<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Directive;

/**
 * @method \Graphpinator\Parser\Directive\Directive current() : object
 * @method \Graphpinator\Parser\Directive\Directive offsetGet($offset) : object
 */
final class DirectiveSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Directive::class;
}
