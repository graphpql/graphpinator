<?php

declare(strict_types = 1);

namespace Graphpinator;

use \Graphpinator\Value\TypeValue;

final class Result implements \JsonSerializable
{
    public function __construct(
        private ?TypeValue $data = null,
        private ?array $errors = null,
    )
    {
    }

    public function getData() : ?TypeValue
    {
        return $this->data;
    }

    public function getErrors() : ?array
    {
        return $this->errors;
    }

    public function jsonSerialize() : \stdClass
    {
        $return = new \stdClass();

        if ($this->data instanceof TypeValue) {
            $return->data = $this->data;
        }

        if (\is_array($this->errors)) {
            $return->errors = $this->errors;
        }

        return $return;
    }

    public function toString() : string
    {
        return \Infinityloop\Utils\Json::fromNative($this->jsonSerialize())->toString();
    }
}
