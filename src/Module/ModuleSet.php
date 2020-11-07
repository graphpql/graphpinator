<?php

declare(strict_types = 1);

namespace Graphpinator\Module;

/**
 * @method \Graphpinator\Module\Module current() : object
 * @method \Graphpinator\Module\Module offsetGet($offset) : object
 */
final class ModuleSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Module::class;
}
