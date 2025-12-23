<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Value\Visitor\GetResolverValueVisitor;
use Graphpinator\Value\Visitor\IsValueSameVisitor;
use Infinityloop\Utils\ImplicitObjectMap;

/**
 * @method ArgumentValue current() : object
 * @method ArgumentValue offsetGet($offset) : object
 */
final class ArgumentValueSet extends ImplicitObjectMap
{
    protected const INNER_CLASS = ArgumentValue::class;

    public function getValuesForResolver() : array
    {
        $return = [];

        foreach ($this as $name => $argumentValue) {
            $return[$name] = $argumentValue->value->accept(new GetResolverValueVisitor());
        }

        return $return;
    }

    public function applyVariables(VariableValueSet $variables) : void
    {
        foreach ($this as $value) {
            $value->applyVariables($variables);
        }
    }

    public function isSame(self $compare) : bool
    {
        foreach ($compare as $lhs) {
            if ($this->offsetExists($lhs->argument->getName())) {
                if ($lhs->value->accept(new IsValueSameVisitor($this->offsetGet($lhs->argument->getName())->value))) {
                    continue;
                }

                return false;
            }

            if ($lhs->value->accept(new IsValueSameVisitor($lhs->argument->getDefaultValue()?->value))) {
                continue;
            }

            return false;
        }

        foreach ($this as $lhs) {
            if ($compare->offsetExists($lhs->argument->getName()) ||
                $lhs->value->accept(new IsValueSameVisitor($lhs->argument->getDefaultValue()?->value))) {
                continue;
            }

            return false;
        }

        return true;
    }

    #[\Override]
    protected function getKey(object $object) : string
    {
        \assert($object instanceof ArgumentValue);

        return $object->argument->getName();
    }
}
