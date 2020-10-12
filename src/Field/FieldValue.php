<?php

declare(strict_types = 1);

namespace Graphpinator\Field;

final class FieldValue implements \JsonSerializable
{
    use \Nette\SmartObject;

    private \Graphpinator\Field\Field $field;
    private \Graphpinator\Value\ResolvedValue $value;

    public function __construct(\Graphpinator\Field\Field $field, \Graphpinator\Value\ResolvedValue $value)
    {
        $this->field = $field;
        $this->value = $value;

        $field->validateConstraints($this->value);
    }

    public function jsonSerialize() : \Graphpinator\Value\ResolvedValue
    {
        return $this->value;
    }

    public function getField() : \Graphpinator\Field\Field
    {
        return $this->field;
    }

    public function getValue() : \Graphpinator\Value\ResolvedValue
    {
        return $this->value;
    }
}
