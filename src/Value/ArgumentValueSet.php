<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class ArgumentValueSet extends \Infinityloop\Utils\ImmutableSet
{
    public function __construct(
        \Graphpinator\Parser\Value\NamedValueSet $namedValueSet,
        \Graphpinator\Argument\ArgumentSet $argumentSet
    ) {
        foreach ($namedValueSet as $namedValue) {
            if (!$argumentSet->offsetExists($namedValue->getName())) {
                throw new \Exception('Unknown argument');
            }
        }

        foreach ($argumentSet as $argument) {
            if ($namedValueSet->offsetExists($argument->getName())) {
                $this->appendUnique($argument->getName(), $namedValueSet[$argument->getName()]->validate($argument->getType()));

                continue;
            }

            if ($argument->getDefaultValue() instanceof ValidatedValue) {
                $this->appendUnique($argument->getName(), $argument->getDefaultValue());

                continue;
            }

            $this->appendUnique($argument->getName(), new NullValue($argument->getType()));
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
