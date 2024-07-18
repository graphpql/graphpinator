<?php

declare(strict_types = 1);

namespace Graphpinator\Module;

use Infinityloop\Utils\ObjectSet;

/**
 * @method Module current() : object
 * @method Module offsetGet($offset) : object
 */
final class ModuleSet extends ObjectSet
{
    protected const INNER_CLASS = Module::class;
}
