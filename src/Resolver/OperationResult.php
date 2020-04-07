<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class OperationResult implements \JsonSerializable
{
    use \Nette\SmartObject;

    private ?array $data;
    private ?array $errors;

    public function __construct(?array $data = null, ?array $errors = null)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    public function getData() : ?array
    {
        return $this->data;
    }

    public function getErrors() : ?array
    {
        return $this->errors;
    }

    public function jsonSerialize()
    {
        $return = [];

        if (\is_array($this->data)) {
            $return['data'] = $this->data;
        }

        if (\is_array($this->errors)) {
            $return['errors'] = $this->errors;
        }

        return $return;
    }
}
