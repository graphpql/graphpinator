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
    private \Graphpinator\Module\ModuleSet $modules;
    private bool $catchExceptions;

    public function __construct(
        \Graphpinator\Type\Schema $schema,
        bool $catchExceptions = false,
        ?\Graphpinator\Module\ModuleSet $modules = null
    )
    {
        $this->schema = $schema;
        $this->catchExceptions = $catchExceptions;
        $this->modules = $modules instanceof \Graphpinator\Module\ModuleSet
            ? $modules
            : new \Graphpinator\Module\ModuleSet([]);
    }

    public function runQuery(\Graphpinator\Json $request) : \Graphpinator\Response
    {
        try {
            $this->validateRequest($request);

            $query = $request[self::QUERY];
            $variables = $request[self::VARIABLES]
                ?? new \stdClass();
            $operationName = $request[self::OPERATION_NAME]
                ?? null;

            $requestObject = \Graphpinator\Parser\Parser::parseString($query)
                ->normalize($this->schema)
                ->createRequest($operationName, $variables);

            foreach ($this->modules as $module) {
                $requestObject = $module->process($requestObject);
            }

            return $requestObject->execute();
        } catch (\Throwable $exception) {
            if (!$this->catchExceptions) {
                throw $exception;
            }

            return new \Graphpinator\Response(null, [
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
