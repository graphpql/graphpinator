<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

use Graphpinator\Typesystem\InputType;
use Graphpinator\Value\Contract\InputedValue;
use Graphpinator\Value\Contract\InputedValueVisitor;

/**
 * @implements \IteratorAggregate<ArgumentValue>
 */
final readonly class InputValue implements InputedValue, \IteratorAggregate
{
    public function __construct(
        public InputType $type,
        public \stdClass $value,
    )
    {
    }

    #[\Override]
    public function accept(InputedValueVisitor $visitor) : mixed
    {
        return $visitor->visitInput($this);
    }

    #[\Override]
    public function getRawValue() : object
    {
        $return = new \stdClass();

        foreach ($this as $argumentName => $argumentValue) {
            $return->{$argumentName} = $argumentValue->value->getRawValue();
        }

        return $return;
    }

    #[\Override]
    public function getType() : InputType
    {
        return $this->type;
    }

    /**
     * @return \ArrayIterator<ArgumentValue>
     */
    #[\Override]
    public function getIterator() : \ArrayIterator
    {
        return new \ArrayIterator((array) $this->value);
    }
}
