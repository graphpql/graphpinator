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

    public function runQuery(\Infinityloop\Utils\Json $request) : \Graphpinator\Resolver\OperationResult
    {
        try {
            $this->validateRequest($request);

            $query = $request[self::QUERY];
            $variables = $request[self::VARIABLES]
                ?? [];
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

    private function validateRequest(\Infinityloop\Utils\Json $request) : void
    {
        if (!isset($request[self::QUERY])) {
            throw new \Graphpinator\Exception\RequestWithoutQuery();
        }

        if (!\is_string($request[self::QUERY])) {
            throw new \Graphpinator\Exception\RequestQueryNotString();
        }

        if (isset($request[self::VARIABLES]) && !\is_array($request[self::VARIABLES])) {
            throw new \Graphpinator\Exception\RequestVariablesNotArray();
        }

        if (isset($request[self::OPERATION_NAME]) && !\is_string($request[self::OPERATION_NAME])) {
            throw new \Graphpinator\Exception\RequestOperationNameNotString();
        }

        foreach ($request as $key => $value) {
            if (!\array_key_exists($key, [self::QUERY => 1, self::VARIABLES => 1, self::OPERATION_NAME => 1])) {
                throw new \Graphpinator\Exception\RequestUnknownKey();
            }
        }
    }
}
