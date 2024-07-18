<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\VariableValueSet;
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
            $return[$name] = $argumentValue->getValue()->getRawValue(true);
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
            if ($this->offsetExists($lhs->getArgument()->getName())) {
                if ($lhs->getValue()->isSame($this->offsetGet($lhs->getArgument()->getName())->getValue())) {
                    continue;
                }

                return false;
            }

            if ($lhs->getValue()->isSame($lhs->getArgument()->getDefaultValue()?->getValue())) {
                continue;
            }

            return false;
        }

        foreach ($this as $lhs) {
            if ($compare->offsetExists($lhs->getArgument()->getName()) ||
                $lhs->getValue()->isSame($lhs->getArgument()->getDefaultValue()?->getValue())) {
                continue;
            }

            return false;
        }

        return true;
    }

    protected function getKey(object $object) : string
    {
        \assert($object instanceof ArgumentValue);

        return $object->getArgument()->getName();
    }
}
