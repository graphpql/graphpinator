<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class JsonRequestFactory implements RequestFactory
{
    use \Nette\SmartObject;

    public const QUERY = 'query';
    public const VARIABLES = 'variables';
    public const OPERATION_NAME = 'operationName';

    private \Graphpinator\Json $json;
    private bool $strict;

    public function __construct(\Graphpinator\Json $json, bool $strict = true)
    {
        $this->json = $json;
        $this->strict = $strict;
    }

    public function create() : Request
    {
        if (!isset($this->json[self::QUERY])) {
            throw new \Graphpinator\Exception\Request\QueryMissing();
        }

        if (!\is_string($this->json[self::QUERY])) {
            throw new \Graphpinator\Exception\Request\QueryNotString();
        }

        if (isset($this->json[self::VARIABLES]) && !$this->json[self::VARIABLES] instanceof \stdClass) {
            throw new \Graphpinator\Exception\Request\VariablesNotObject();
        }

        if (isset($this->json[self::OPERATION_NAME]) && !\is_string($this->json[self::OPERATION_NAME])) {
            throw new \Graphpinator\Exception\Request\OperationNameNotString();
        }

        if ($this->strict) {
            foreach ($this->json as $key => $value) {
                if (!\in_array($key, [self::QUERY, self::VARIABLES, self::OPERATION_NAME], true)) {
                    throw new \Graphpinator\Exception\Request\UnknownKey();
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
