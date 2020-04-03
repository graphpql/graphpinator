<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ArgumentValueSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(
        \Graphpinator\Parser\Value\NamedValueSet $namedValueSet,
        \Graphpinator\Argument\ArgumentSet $argumentSet,
        ValidatedValueSet $variables
    ) {
        foreach ($argumentSet as $argument) {
            if ($namedValueSet->offsetExists($argument->getName())) {
                $rawValue = $namedValueSet[$argument->getName()]->normalizeValue($variables);
                $this->appendUnique($argument->getName(), $argument->getType()->createValue($rawValue));

                continue;
            }

            if ($argument->getDefaultValue() instanceof ValidatedValue) {
                $this->appendUnique($argument->getName(), $argument->getDefaultValue());

                continue;
            }

            $this->appendUnique($argument->getName(), new NullValue($argument->getType()));
        }

        foreach ($namedValueSet as $namedValue) {
            if (!$argumentSet->offsetExists($namedValue->getName())) {
                throw new \Exception('Unknown argument');
            }
        }
    }

    public function current() : ValidatedValue
    {
        return parent::current();
    }

    public function offsetGet($offset) : ValidatedValue
    {
        return parent::offsetGet($offset);
    }
}
