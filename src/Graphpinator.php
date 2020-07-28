<?php

declare(strict_types = 1);

namespace Graphpinator;

final class Graphpinator
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Schema $schema;
    private bool $catchExceptions;

    public function __construct(\Graphpinator\Type\Schema $schema, bool $catchExceptions = false)
    {
        $this->schema = $schema;
        $this->catchExceptions = $catchExceptions;
    }

    public function runQuery(\Infinityloop\Utils\Json $request) : \Graphpinator\Resolver\OperationResult
    {
        $this->validateRequest($request);

        $query = $request['query'];
        $variables = $request['variables'] ?? [];
        $operationName = $request['operationName'] ?? null;

        try {
            return \Graphpinator\Parser\Parser::parseString($query)
                ->normalize($this->schema)
                ->execute($variables);
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
        if (!isset($request['query'])) {
            throw new \Graphpinator\Exception\RequestWithoutQuery();
        }

        if (!\is_string($request['query'])) {
            throw new \Graphpinator\Exception\RequestQueryNotString();
        }

        if (isset($request['variables']) && !\is_array($request['variables'])) {
            throw new \Graphpinator\Exception\RequestVariablesNotArray();
        }

        if (isset($request['operationName']) && !\is_string($request['operationName'])) {
            throw new \Graphpinator\Exception\RequestOperationNameNotString();
        }
    }
}
