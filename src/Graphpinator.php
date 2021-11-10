<?php

declare(strict_types = 1);

namespace Graphpinator;

use \Graphpinator\Exception\GraphpinatorBase;
use \Graphpinator\Module\ModuleSet;
use \Psr\Log\LogLevel;
use \Psr\Log\LoggerInterface;

final class Graphpinator implements \Psr\Log\LoggerAwareInterface
{
    use \Nette\SmartObject;

    public static bool $validateSchema = true;
    private ModuleSet $modules;
    private LoggerInterface $logger;
    private \Graphpinator\Parser\Parser $parser;
    private \Graphpinator\Normalizer\Normalizer $normalizer;
    private \Graphpinator\Normalizer\Finalizer $finalizer;
    private \Graphpinator\Resolver\Resolver $resolver;

    public function __construct(
        \Graphpinator\Typesystem\Schema $schema,
        private bool $catchExceptions = false,
        ?ModuleSet $modules = null,
        ?LoggerInterface $logger = null,
    )
    {
        $this->modules = $modules instanceof ModuleSet
            ? $modules
            : new ModuleSet([]);
        $this->logger = $logger instanceof LoggerInterface
            ? $logger
            : new \Psr\Log\NullLogger();
        $this->parser = new \Graphpinator\Parser\Parser();
        $this->normalizer = new \Graphpinator\Normalizer\Normalizer($schema);
        $this->finalizer = new \Graphpinator\Normalizer\Finalizer();
        $this->resolver = new \Graphpinator\Resolver\Resolver();
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
                $result = $this->finalizer->finalize($result, $request->getVariables(), $request->getOperationName());

                foreach ($this->modules as $module) {
                    $result = $module->processFinalized($result);
                }
            }

            $result = $this->resolver->resolve($result);

            foreach ($this->modules as $module) {
                $result = $module->processResult($result);
            }

            return $result;
        } catch (\Throwable $exception) {
            if (!$this->catchExceptions) {
                throw $exception;
            }

            $this->logger->log(self::getLogLevel($exception), self::getLogMessage($exception));

            return new \Graphpinator\Result(null, [
                $exception instanceof GraphpinatorBase
                    ? $exception
                    : \Graphpinator\Exception\GraphpinatorBase::notOutputableResponse(),
            ]);
        }
    }

    public function setLogger(LoggerInterface $logger) : void
    {
        $this->logger = $logger;
    }

    private static function getLogMessage(\Throwable $exception) : string
    {
        return $exception->getMessage() . ' in ' . $exception->getFile() . ':' . $exception->getLine();
    }

    private static function getLogLevel(\Throwable $exception) : string
    {
        if ($exception instanceof \Graphpinator\Exception\GraphpinatorBase) {
            return $exception->isOutputable()
                ? LogLevel::INFO
                : \Psr\Log\LogLevel::ERROR;
        }

        return \Psr\Log\LogLevel::EMERGENCY;
    }
}
