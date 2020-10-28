<?php

declare(strict_types = 1);

namespace Graphpinator;

final class Graphpinator implements \Psr\Log\LoggerAwareInterface
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Schema $schema;
    private bool $catchExceptions;
    private \Graphpinator\Module\ModuleSet $modules;
    private \Psr\Log\LoggerInterface $logger;

    public function __construct(
        \Graphpinator\Type\Schema $schema,
        bool $catchExceptions = false,
        ?\Graphpinator\Module\ModuleSet $modules = null,
        ?\Psr\Log\LoggerInterface $logger = null
    )
    {
        $this->schema = $schema;
        $this->catchExceptions = $catchExceptions;
        $this->modules = $modules instanceof \Graphpinator\Module\ModuleSet
            ? $modules
            : new \Graphpinator\Module\ModuleSet([]);
        $this->logger = $logger instanceof \Psr\Log\LoggerInterface
            ? $logger
            : new \Psr\Log\NullLogger();
    }

    public function run(\Graphpinator\Request\RequestFactory $requestFactory) : \Graphpinator\Response
    {
        try {
            $request = $requestFactory->create();

            $this->logger->debug($request->getQuery());

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

            if ($exception instanceof \Graphpinator\Exception\GraphpinatorBase) {
                $this->logger->info($exception->getMessage());

                return new \Graphpinator\Response(null, [$exception]);
            }

            $this->logger->emergency($exception->getMessage());

            return new \Graphpinator\Response(null, [\Graphpinator\Exception\GraphpinatorBase::notOutputableResponse()]);
        }
    }

    public function setLogger(\Psr\Log\LoggerInterface $logger) : void
    {
        $this->logger = $logger;
    }
}
