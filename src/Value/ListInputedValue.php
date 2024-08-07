<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\ListType;

final class ListInputedValue extends ListValue implements InputedValue
{
    public function getRawValue(bool $forResolvers = false) : array
    {
        $return = [];

        foreach ($this->value as $listItem) {
            \assert($listItem instanceof InputedValue);

            $return[] = $listItem->getRawValue($forResolvers);
        }

        return $return;
    }

    public function getType() : ListType
    {
        return $this->type;
    }

    public function printValue() : string
    {
        $component = [];

        foreach ($this->value as $value) {
            \assert($value instanceof InputedValue);

            $component[] = $value->printValue();
        }

        return '[' . \implode(',', $component) . ']';
    }

    public function applyVariables(VariableValueSet $variables) : void
    {
        foreach ($this->value as $value) {
            \assert($value instanceof InputedValue);

            $value->applyVariables($variables);
        }
    }

    public function resolveRemainingDirectives() : void
    {
        foreach ($this->value as $value) {
            \assert($value instanceof InputedValue);

            $value->resolveRemainingDirectives();
        }
    }

    public function isSame(Value $compare) : bool
    {
        if (!$compare instanceof self) {
            return false;
        }

        $secondArray = $compare->value;

        if (\count($secondArray) !== \count($this->value)) {
            return false;
        }

        foreach ($this->value as $key => $value) {
            \assert($value instanceof InputedValue);

            if (!\array_key_exists($key, $secondArray) || !$value->isSame($secondArray[$key])) {
                return false;
            }
        }

        return true;
    }
}
