<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class JsonRequestFactory implements \Graphpinator\Request\RequestFactory
{
    use \Nette\SmartObject;

    public const QUERY = 'query';
    public const VARIABLES = 'variables';
    public const OPERATION_NAME = 'operationName';

    public function __construct(
        private \Infinityloop\Utils\Json\JsonContract $json,
        private bool $strict = true,
    )
    {
    }

    public static function fromString(string $json, bool $strict = true) : self
    {
        return new self(\Infinityloop\Utils\Json\MapJson::fromString($json), $strict);
    }

    public function create() : Request
    {
        if (!isset($this->json[self::QUERY])) {
            throw new \Graphpinator\Request\Exception\QueryMissing();
        }

        if (!\is_string($this->json[self::QUERY])) {
            throw new \Graphpinator\Request\Exception\QueryNotString();
        }

        if (isset($this->json[self::VARIABLES]) && !$this->json[self::VARIABLES] instanceof \stdClass) {
            throw new \Graphpinator\Request\Exception\VariablesNotObject();
        }

        if (isset($this->json[self::OPERATION_NAME]) && !\is_string($this->json[self::OPERATION_NAME])) {
            throw new \Graphpinator\Request\Exception\OperationNameNotString();
        }

        if ($this->strict) {
            foreach ($this->json as $key => $value) {
                if (!\in_array($key, [self::QUERY, self::VARIABLES, self::OPERATION_NAME], true)) {
                    throw new \Graphpinator\Request\Exception\UnknownKey();
                }
            }
        }

        $query = $this->json[self::QUERY];
        $variables = $this->json[self::VARIABLES]
            ?? new \stdClass();
        $operationName = $this->json[self::OPERATION_NAME]
            ?? null;

        return new \Graphpinator\Request\Request($query, $variables, $operationName);
    }
}
