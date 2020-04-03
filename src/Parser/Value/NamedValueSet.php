<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class NamedValueSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(array $values)
    {
        foreach ($values as $value) {
            if ($value instanceof \Graphpinator\Parser\Value\NamedValue) {
                $this->appendUnique($value->getName(), $value);

                continue;
            }

            throw new \Exception();
        }
    }

    public function current() : NamedValue
    {
        return parent::current();
    }

    public function offsetGet($offset) : NamedValue
    {
        return parent::offsetGet($offset);
    }

    public function normalize(
        \Graphpinator\Argument\ArgumentSet $arguments,
        \Graphpinator\Value\ValidatedValueSet $variables
    ) : \Graphpinator\Value\ArgumentValueSet
    {
        return new \Graphpinator\Value\ArgumentValueSet($this, $arguments, $variables);
    }
}
