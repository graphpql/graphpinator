<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Variable;

/**
 * @method Variable current() : object
 * @method Variable offsetGet($offset) : object
 */
final class VariableSet extends \Infinityloop\Utils\ImplicitObjectMap
{
    protected const INNER_CLASS = Variable::class;

    public function normalize(\Graphpinator\Container\Container $typeContainer) : \Graphpinator\Normalizer\Variable\VariableSet
    {
        $variables = [];

        foreach ($this as $variable) {
            $variables[] = $variable->normalize($typeContainer);
        }

        return new \Graphpinator\Normalizer\Variable\VariableSet($variables);
    }

    protected function getKey(object $object) : string
    {
        return $object->getName();
    }
}
