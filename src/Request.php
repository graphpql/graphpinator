<?php

declare(strict_types = 1);

namespace Graphpinator;

class Request
{
    use \Nette\SmartObject;

    public const QUERY = 'query';
    public const VARIABLES = 'variables';
    public const OPERATION_NAME = 'operationName';

    private string $query;
    private ?\stdClass $variables;
    private ?string $operationName;

    final public function __construct(string $query, ?\stdClass $variables = null, ?string $operationName = null)
    {
        $this->query = $query;
        $this->variables = $variables
            ?? new \stdClass();
        $this->operationName = $operationName;
    }

    final public static function fromJson(\Graphpinator\Json $input, bool $strict = true) : self
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

        if ($strict) {
            foreach ($input as $key => $value) {
                if (!\in_array($key, [self::QUERY, self::VARIABLES, self::OPERATION_NAME], true)) {
                    throw new \Graphpinator\Exception\Request\UnknownKey();
                }
            }
        }

        $query = $input[self::QUERY];
        $variables = $input[self::VARIABLES]
            ?? new \stdClass();
        $operationName = $input[self::OPERATION_NAME]
            ?? null;

        return new self($query, $variables, $operationName);
    }

    final public static function fromHttpRequest(\Psr\Http\Message\ServerRequestInterface $request, bool $strict = true) : self
    {
        $method = $request->getMethod();

        if (!\in_array($method, ['GET', 'POST'], true)) {
            throw new \Graphpinator\Exception\Request\InvalidMethod();
        }

        $contentTypes = $request->getHeader('Content-Type');
        $contentType = \array_pop($contentTypes);

        if (\is_string($contentType) && \str_starts_with($contentType, 'multipart/form-data')) {
            if ($method === 'POST' && \array_key_exists('operations', $request->getParsedBody())) {
                return self::fromJson(Json::fromString($request->getParsedBody()['operations']), $strict);
            }

            throw new \Graphpinator\Exception\Request\InvalidMultipartRequest();
        }

        switch ($contentType) {
            case 'application/graphql':
                return new self($request->getBody()->getContents());
            case 'application/json':
                return self::fromJson(Json::fromString($request->getBody()->getContents()), $strict);
            default:
                $params = $request->getQueryParams();

                if (\array_key_exists('variables', $params)) {
                    $params['variables'] = \Graphpinator\Json::fromString($params['variables'])->toObject();
                }

                return self::fromJson(Json::fromObject((object) $params), $strict);
        }
    }

    final public function getQuery() : string
    {
        return $this->query;
    }

    final public function getVariables() : ?\stdClass
    {
        return $this->variables;
    }

    final public function getOperationName() : ?string
    {
        return $this->operationName;
    }
}
