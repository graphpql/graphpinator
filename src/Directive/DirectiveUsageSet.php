<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

/**
 * @method \Graphpinator\Directive\DirectiveUsage current() : object
 * @method \Graphpinator\Directive\DirectiveUsage offsetGet($offset) : object
 */
final class DirectiveUsageSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = DirectiveUsage::class;
}
