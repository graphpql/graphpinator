<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

/**
 * @method \Graphpinator\Argument\ArgumentValue current() : object
 * @method \Graphpinator\Argument\ArgumentValue offsetGet($offset) : object
 */
final class ArgumentValueSet extends \Infinityloop\Utils\ObjectMap
{
    protected const INNER_CLASS = ArgumentValue::class;

    public function __construct(
        \Graphpinator\Parser\Value\NamedValueSet $namedValueSet,
        \Graphpinator\Argument\ArgumentSet $argumentSet
    )
    {
        parent::__construct();

        foreach ($argumentSet as $argument) {
            $value = $namedValueSet->offsetExists($argument->getName())
                ? $namedValueSet[$argument->getName()]->getRawValue()
                : null;

            $this->offsetSet($argument->getName(), ArgumentValue::fromRaw($argument, $value));
        }
    }

    public function getRawValues() : array
    {
        $return = [];

        foreach ($this as $argumentValue) {
            $return[] = $argumentValue->getValue()->getRawValue();
        }

        return $return;
    }
}
