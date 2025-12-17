<?php

declare(strict_types = 1);

namespace Graphpinator;

use Graphpinator\Exception\ClientAware;
use Graphpinator\Module\ModuleSet;
use Graphpinator\Normalizer\Finalizer;
use Graphpinator\Normalizer\NormalizedRequest;
use Graphpinator\Normalizer\Normalizer;
use Graphpinator\Parser\ParsedRequest;
use Graphpinator\Parser\Parser;
use Graphpinator\Request\Request;
use Graphpinator\Request\RequestFactory;
use Graphpinator\Resolver\ErrorHandlingMode;
use Graphpinator\Resolver\ExceptionHandler;
use Graphpinator\Resolver\Resolver;
use Graphpinator\Resolver\Result;
use Graphpinator\Source\StringSource;
use Graphpinator\Typesystem\Schema;
use Psr\Log\LogLevel;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

final class Graphpinator implements LoggerAwareInterface
{
    /**
     * Whether Graphpinator should perform schema integrity checks. Disable in production to avoid unnecessary overhead.
     */
    public static bool $validateSchema = true;
    private ExceptionHandler $exceptionHandler;
    private Parser $parser;
    private Normalizer $normalizer;
    private Finalizer $finalizer;
    private Resolver $resolver;

    public function __construct(
        Schema $schema,
        bool|ErrorHandlingMode $errorHandlingMode = ErrorHandlingMode::NONE,
        private ModuleSet $modules = new ModuleSet([]),
        private LoggerInterface $logger = new NullLogger(),
    )
    {
        $this->exceptionHandler = new ExceptionHandler($errorHandlingMode);
        $this->parser = new Parser();
        $this->normalizer = new Normalizer($schema);
        $this->finalizer = new Finalizer();
        $this->resolver = new Resolver();
    }

    public function run(RequestFactory $requestFactory) : Result
    {
        try {
            $request = $requestFactory->create();
            $result = $request;

            $this->logger->debug($request->query);

            foreach ($this->modules as $module) {
                $result = $module->processRequest($request);

                if (!$result instanceof Request) {
                    break;
                }
            }

            if ($result instanceof Request) {
                $result = $this->parser->parse(new StringSource($request->query));

                foreach ($this->modules as $module) {
                    $result = $module->processParsed($result);

                    if (!$result instanceof ParsedRequest) {
                        break;
                    }
                }
            }

            if ($result instanceof ParsedRequest) {
                $result = $this->normalizer->normalize($result);

                foreach ($this->modules as $module) {
                    $result = $module->processNormalized($result);

                    if (!$result instanceof NormalizedRequest) {
                        break;
                    }
                }
            }

            if ($result instanceof NormalizedRequest) {
                $result = $this->finalizer->finalize($result, $request->variables, $request->operationName);

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
            $this->logger->log(self::getLogLevel($exception), self::getLogMessage($exception));

            return $this->exceptionHandler->handle($exception);
        }
    }

    #[\Override]
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
        if ($exception instanceof ClientAware) {
            return $exception->isOutputable()
                ? LogLevel::INFO
                : LogLevel::ERROR;
        }

        return LogLevel::EMERGENCY;
    }
}
