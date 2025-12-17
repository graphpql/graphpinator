<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\DirectiveUsage;

use Infinityloop\Utils\ObjectSet;

/**
 * @method DirectiveUsage current() : object
 * @method DirectiveUsage offsetGet($offset) : object
 */
final class DirectiveUsageSet extends ObjectSet
{
    protected const INNER_CLASS = DirectiveUsage::class;
}
