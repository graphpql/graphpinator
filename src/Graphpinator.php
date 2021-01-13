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

    public function run(\Graphpinator\Request\RequestFactory $requestFactory) : \Graphpinator\OperationResponse
    {
        try {
            $request = $requestFactory->create();
            $result = $request;

            $this->logger->debug($request->getQuery());

            foreach ($this->modules as $module) {
                $result = $module->processRequest($request);

                if (!$result instanceof \Graphpinator\Request\Request) {
                    break;
                }
            }

            if ($result instanceof \Graphpinator\Request\Request) {
                $result = \Graphpinator\Parser\Parser::parseString($request->getQuery());

                foreach ($this->modules as $module) {
                    $result = $module->processParsed($result);

                    if (!$result instanceof \Graphpinator\Parser\ParsedRequest) {
                        break;
                    }
                }
            }

            if ($result instanceof \Graphpinator\Parser\ParsedRequest) {
                $result = $result->normalize($this->schema);

                foreach ($this->modules as $module) {
                    $result = $module->processNormalized($result);

                    if (!$result instanceof \Graphpinator\Normalizer\NormalizedRequest) {
                        break;
                    }
                }
            }

            if ($result instanceof \Graphpinator\Normalizer\NormalizedRequest) {
                $result = $result->finalize($request->getVariables(), $request->getOperationName());

                foreach ($this->modules as $module) {
                    $result = $module->processFinalized($result);

                    if (!$result instanceof OperationRequest) {
                        break;
                    }
                }
            }

            return $result->execute();
        } catch (\Throwable $exception) {
            if (!$this->catchExceptions) {
                throw $exception;
            }

            if ($exception instanceof \Graphpinator\Exception\GraphpinatorBase) {
                $this->logger->info($exception->getMessage());

                return new \Graphpinator\OperationResponse(null, [$exception]);
            }

            $this->logger->emergency($exception->getMessage());

            return new \Graphpinator\OperationResponse(null, [\Graphpinator\Exception\GraphpinatorBase::notOutputableResponse()]);
        }
    }

    public function setLogger(\Psr\Log\LoggerInterface $logger) : void
    {
        $this->logger = $logger;
    }
}
