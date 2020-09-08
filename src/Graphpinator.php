<?php

declare(strict_types = 1);

namespace Graphpinator;

final class Graphpinator
{
    use \Nette\SmartObject;

    public const QUERY = 'query';
    public const VARIABLES = 'variables';
    public const OPERATION_NAME = 'operationName';

    private \Graphpinator\Type\Schema $schema;
    private bool $catchExceptions;

    public function __construct(\Graphpinator\Type\Schema $schema, bool $catchExceptions = false)
    {
        $this->schema = $schema;
        $this->catchExceptions = $catchExceptions;
    }

    public function runQuery(\Graphpinator\Json $request) : \Graphpinator\Resolver\OperationResult
    {
        try {
            $this->validateRequest($request);

            $query = $request[self::QUERY];
            $variables = $request[self::VARIABLES]
                ?? new \stdClass();
            $operationName = $request[self::OPERATION_NAME]
                ?? null;

            return \Graphpinator\Parser\Parser::parseString($query)
                ->normalize($this->schema)
                ->execute($operationName, $variables);
        } catch (\Throwable $exception) {
            if (!$this->catchExceptions) {
                throw $exception;
            }

            return new \Graphpinator\Resolver\OperationResult(null, [
                $exception instanceof \Graphpinator\Exception\GraphpinatorBase
                    ? $exception
                    : \Graphpinator\Exception\GraphpinatorBase::notOutputableResponse(),
            ]);
        }
    }

    private function validateRequest(\Graphpinator\Json $request) : void
    {
        if (!isset($request[self::QUERY])) {
            throw new \Graphpinator\Exception\Request\QueryMissing();
        }

        if (!\is_string($request[self::QUERY])) {
            throw new \Graphpinator\Exception\Request\QueryNotString();
        }

        if (isset($request[self::VARIABLES]) && !$request[self::VARIABLES] instanceof \stdClass) {
            throw new \Graphpinator\Exception\Request\VariablesNotObject();
        }

        if (isset($request[self::OPERATION_NAME]) && !\is_string($request[self::OPERATION_NAME])) {
            throw new \Graphpinator\Exception\Request\OperationNameNotString();
        }

        foreach ($request as $key => $value) {
            if (!\in_array($key, [self::QUERY, self::VARIABLES, self::OPERATION_NAME], true)) {
                throw new \Graphpinator\Exception\Request\UnknownKey();
            }
        }
    }
}
