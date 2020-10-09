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

    public function runQuery(Request $request) : \Graphpinator\Response
    {
        try {
            $parsedRequest = \Graphpinator\Parser\Parser::parseString($request->getQuery())
                ->normalize($this->schema)
                ->createRequest($request->getOperationName(), $request->getVariables());

            foreach ($this->modules as $module) {
                $parsedRequest = $module->process($parsedRequest);
            }

            return $parsedRequest->execute();
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
}
