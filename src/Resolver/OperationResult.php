<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

final class OperationResult implements \JsonSerializable
{
    use \Nette\SmartObject;

    private ?\stdClass $data;
    private ?array $errors;

    public function __construct(?\stdClass $data = null, ?array $errors = null)
    {
        $this->data = $data;
        $this->errors = $errors;
    }

    public function getData() : ?\stdClass
    {
        return $this->data;
    }

    public function getErrors() : ?array
    {
        return $this->errors;
    }

    //@phpcs:ignore SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingAnyTypeHint
    public function jsonSerialize()
    {
        $return = [];

        if ($this->data instanceof \stdClass) {
            $return['data'] = $this->data;
        }

        if (\is_array($this->errors)) {
            $return['errors'] = $this->errors;
        }

        return $return;
    }
}
