<?php

declare(strict_types = 1);

namespace Graphpinator\Value;

final class FieldValue implements \JsonSerializable
{
    use \Nette\SmartObject;

    protected \Graphpinator\Field\Field $field;
    protected \Graphpinator\Value\ResolvableValue $value;

    public function __construct($rawValue, \Graphpinator\Field\Field $field)
    {
        $this->value = $field->getType()->createResolvableValue($rawValue);
        $this->field = $field;

        //$field->validateConstraints($rawValue);
    }

    public function jsonSerialize() : ResolvableValue
    {
        return $this->value;
    }

    public function getField() : \Graphpinator\Field\Field
    {
        return $this->field;
    }

    public function getValue() : ResolvableValue
    {
        return $this->value;
    }
}
