<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

use Graphpinator\Request\Exception\OperationNameNotString;
use Graphpinator\Request\Exception\QueryMissing;
use Graphpinator\Request\Exception\QueryNotString;
use Graphpinator\Request\Exception\UnknownKey;
use Graphpinator\Request\Exception\VariablesNotObject;
use Infinityloop\Utils\Json;

final class JsonRequestFactory implements RequestFactory
{
    public const QUERY = 'query';
    public const VARIABLES = 'variables';
    public const OPERATION_NAME = 'operationName';

    public function __construct(
        private Json $json,
        private bool $strict = true, // whether to allow unknown JSON keys in request, enable this to pass additional data in request, e.g. request ID
    )
    {
    }

    public static function fromString(string $json, bool $strict = true) : self
    {
        return new self(Json::fromString($json), $strict);
    }

    public function create() : Request
    {
        if (!isset($this->json[self::QUERY])) {
            throw new QueryMissing();
        }

        if (!\is_string($this->json[self::QUERY])) {
            throw new QueryNotString();
        }

        if (isset($this->json[self::VARIABLES]) && !$this->json[self::VARIABLES] instanceof \stdClass) {
            throw new VariablesNotObject();
        }

        if (isset($this->json[self::OPERATION_NAME]) && !\is_string($this->json[self::OPERATION_NAME])) {
            throw new OperationNameNotString();
        }

        if ($this->strict) {
            foreach ($this->json as $key => $value) {
                if (!\in_array($key, [self::QUERY, self::VARIABLES, self::OPERATION_NAME], true)) {
                    throw new UnknownKey();
                }
            }
        }

        $query = $this->json[self::QUERY];
        $variables = $this->json[self::VARIABLES]
            ?? new \stdClass();
        $operationName = $this->json[self::OPERATION_NAME]
            ?? null;

        return new Request($query, $variables, $operationName);
    }
}
