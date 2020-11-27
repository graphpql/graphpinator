<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Value;

final class ObjectVal implements \Graphpinator\Parser\Value\Value
{
    use \Nette\SmartObject;

    public function __construct(
        private \stdClass $value
    ) {}

    public function getRawValue() : \stdClass
    {
        $return = new \stdClass();

        foreach ($this->value as $key => $value) {
            \assert($value instanceof Value);

            $return->{$key} = $value->getRawValue();
        }

        return $return;
    }

    public function normalize(\Graphpinator\Type\Contract\Inputable $type) : \Graphpinator\Value\InputValue
    {
        if ($type instanceof \Graphpinator\Type\InputType) {

        }

        return new \Graphpinator\Value\InputValue($type, $this->value);
    }
}
