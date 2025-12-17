<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

use Graphpinator\Value\TypeValue;
use Infinityloop\Utils\Json;

final readonly class Result implements \JsonSerializable
{
    public function __construct(
        public ?TypeValue $data = null,
        public ?array $errors = null,
    )
    {
    }

    #[\Override]
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
        return Json::fromNative($this->jsonSerialize())->toString();
    }
}
