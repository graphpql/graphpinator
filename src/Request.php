<?php

declare(strict_types = 1);

namespace Graphpinator;

final class Request
{
    use \Nette\SmartObject;

    public const QUERY = 'query';
    public const VARIABLES = 'variables';
    public const OPERATION_NAME = 'operationName';

    private string $query;
    private ?\stdClass $variables;
    private ?string $operationName;

    public function __construct(string $query, ?\stdClass $variables = null, ?string $operationName = null)
    {
        $this->query = $query;
        $this->variables = $variables
            ?? new \stdClass();
        $this->operationName = $operationName;
    }

    public static function fromJson(\Graphpinator\Json $input) : self
    {
        if (!isset($input[self::QUERY])) {
            throw new \Graphpinator\Exception\Request\QueryMissing();
        }

        if (!\is_string($input[self::QUERY])) {
            throw new \Graphpinator\Exception\Request\QueryNotString();
        }

        if (isset($input[self::VARIABLES]) && !$input[self::VARIABLES] instanceof \stdClass) {
            throw new \Graphpinator\Exception\Request\VariablesNotObject();
        }

        if (isset($input[self::OPERATION_NAME]) && !\is_string($input[self::OPERATION_NAME])) {
            throw new \Graphpinator\Exception\Request\OperationNameNotString();
        }

        foreach ($input as $key => $value) {
            if (!\in_array($key, [self::QUERY, self::VARIABLES, self::OPERATION_NAME], true)) {
                throw new \Graphpinator\Exception\Request\UnknownKey();
            }
        }

        $query = $input[self::QUERY];
        $variables = $input[self::VARIABLES]
            ?? new \stdClass();
        $operationName = $input[self::OPERATION_NAME]
            ?? null;

        return new self($query, $variables, $operationName);
    }

    public static function fromHttpRequest(\Psr\Http\Message\ServerRequestInterface $request) : self
    {
        $contentType = $request->getHeader('Content-Type');

        switch (\array_pop($contentType)) {
            case 'application/graphql':
                return new self($request->getBody()->getContents());
            case 'application/json':
                return self::fromJson(Json::fromString($request->getBody()->getContents()));
            default:
                return self::fromJson(Json::fromObject((object) $request->getQueryParams()));
        }
    }

    public function getQuery() : string
    {
        return $this->query;
    }

    public function getVariables() : ?\stdClass
    {
        return $this->variables;
    }

    public function getOperationName() : ?string
    {
        return $this->operationName;
    }
}
