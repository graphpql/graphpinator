<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Variable;

final class VariableSet extends \Graphpinator\ClassSet
{
    public const INNER_CLASS = Variable::class;

    public function current() : Variable
    {
        return parent::current();
    }

    public function offsetGet($offset) : Variable
    {
        return parent::offsetGet($offset);
    }

    public function normalize(\Graphpinator\Type\Resolver $resolver) : \Graphpinator\Request\Variable\VariableSet
    {
        $variables = [];

        foreach ($this as $variable) {
            $variables[] = $variable->normalize($resolver);
        }

        return new \Graphpinator\Request\Variable\VariableSet($variables);
    }
}
