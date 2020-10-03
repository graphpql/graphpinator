<?php

declare(strict_types = 1);

namespace Graphpinator\Module;

final class ModuleSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Module::class;

    public function current() : Module
    {
        return parent::current();
    }

    public function offsetGet($offset) : Module
    {
        return parent::offsetGet($offset);
    }
}
