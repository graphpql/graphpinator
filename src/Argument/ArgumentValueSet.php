<?php

declare(strict_types = 1);

namespace Graphpinator\Argument;

final class ArgumentValueSet extends \Infinityloop\Utils\ObjectMap
{
    protected const INNER_CLASS = \Graphpinator\Value\InputedValue::class;

    public function __construct(
        \Graphpinator\Parser\Value\NamedValueSet $namedValueSet,
        \Graphpinator\Argument\ArgumentSet $argumentSet
    )
    {
        parent::__construct();

        foreach ($namedValueSet as $namedValue) {
            if (!$argumentSet->offsetExists($namedValue->getName())) {
                throw new \Graphpinator\Exception\Resolver\UnknownArgument();
            }
        }

        foreach ($argumentSet as $argument) {
            if ($namedValueSet->offsetExists($argument->getName())) {
                $value = $argument->getType()->createInputedValue($namedValueSet[$argument->getName()]->getRawValue());
            } elseif ($argument->getDefaultValue() instanceof \Graphpinator\Value\InputedValue) {
                $value = $argument->getDefaultValue();
            } else {
                $value = $argument->getType()->createInputedValue(null);
            }

            $argument->validateConstraints($value);

            $this->array[$argument->getName()] = $value;
        }
    }

    public function getRawValues() : array
    {
        $return = [];

        foreach ($this as $argumentValue) {
            $return[] = $argumentValue->getRawValue();
        }

        return $return;
    }
}
