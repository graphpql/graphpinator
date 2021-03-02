<?php

declare(strict_types = 1);

namespace Graphpinator;

final class Graphpinator implements \Psr\Log\LoggerAwareInterface
{
    use \Nette\SmartObject;

    private bool $catchExceptions;
    private \Graphpinator\Module\ModuleSet $modules;
    private \Psr\Log\LoggerInterface $logger;
    private \Graphpinator\Parser\Parser $parser;
    private \Graphpinator\Normalizer\Normalizer $normalizer;

    public function __construct(
        \Graphpinator\Type\Schema $schema,
        bool $catchExceptions = false,
        ?\Graphpinator\Module\ModuleSet $modules = null,
        ?\Psr\Log\LoggerInterface $logger = null
    )
    {
        $this->catchExceptions = $catchExceptions;
        $this->modules = $modules instanceof \Graphpinator\Module\ModuleSet
            ? $modules
            : new \Graphpinator\Module\ModuleSet([]);
        $this->logger = $logger instanceof \Psr\Log\LoggerInterface
            ? $logger
            : new \Psr\Log\NullLogger();
        $this->parser = new \Graphpinator\Parser\Parser();
        $this->normalizer = new \Graphpinator\Normalizer\Normalizer($schema);
    }

    public function run(\Graphpinator\Request\RequestFactory $requestFactory) : \Graphpinator\Result
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
                $result = $this->parser->parse(new \Graphpinator\Source\StringSource($request->getQuery()));

                foreach ($this->modules as $module) {
                    $result = $module->processParsed($result);

                    if (!$result instanceof \Graphpinator\Parser\ParsedRequest) {
                        break;
                    }
                }
            }

            if ($result instanceof \Graphpinator\Parser\ParsedRequest) {
                $result = $this->normalizer->normalize($result);

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
                }
            }

            return $result->execute();
        } catch (\Throwable $exception) {
            if (!$this->catchExceptions) {
                throw $exception;
            }

            if ($exception instanceof \Graphpinator\Exception\GraphpinatorBase) {
                $this->logger->info($exception->getMessage());

                return new \Graphpinator\Result(null, [$exception]);
            }

            $this->logger->emergency($exception->getMessage());

            return new \Graphpinator\Result(null, [\Graphpinator\Exception\GraphpinatorBase::notOutputableResponse()]);
        }
    }

    public function setLogger(\Psr\Log\LoggerInterface $logger) : void
    {
        $this->logger = $logger;
    }
}
