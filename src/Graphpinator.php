<?php

declare(strict_types = 1);

namespace Graphpinator;

use Graphpinator\Common\Location;
use Graphpinator\Common\Path;
use Graphpinator\Exception\ClientAware;
use Graphpinator\Module\ModuleSet;
use Graphpinator\Normalizer\Finalizer;
use Graphpinator\Normalizer\NormalizedRequest;
use Graphpinator\Normalizer\Normalizer;
use Graphpinator\Parser\ParsedRequest;
use Graphpinator\Parser\Parser;
use Graphpinator\Request\Request;
use Graphpinator\Request\RequestFactory;
use Graphpinator\Resolver\Resolver;
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
    private ErrorHandlingMode $errorHandlingMode;
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
        $this->errorHandlingMode = $errorHandlingMode instanceof ErrorHandlingMode
            ? $errorHandlingMode
            : ErrorHandlingMode::fromBool($errorHandlingMode);
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

            $this->logger->debug($request->getQuery());

            foreach ($this->modules as $module) {
                $result = $module->processRequest($request);

                if (!$result instanceof Request) {
                    break;
                }
            }

            if ($result instanceof Request) {
                $result = $this->parser->parse(new StringSource($request->getQuery()));

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
            $this->logger->log(self::getLogLevel($exception), self::getLogMessage($exception));

            return match ($this->errorHandlingMode) {
                ErrorHandlingMode::ALL => $this->handleAll($exception),
                ErrorHandlingMode::OUTPUTABLE => $this->handleOutputable($exception),
                ErrorHandlingMode::CLIENT_AWARE => $this->handleClientAware($exception),
                ErrorHandlingMode::NONE => $this->handleNone($exception),
            };
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
        if ($exception instanceof ClientAware) {
            return $exception->isOutputable()
                ? LogLevel::INFO
                : LogLevel::ERROR;
        }

        return LogLevel::EMERGENCY;
    }

    private static function serializeError(ClientAware $exception) : array
    {
        if (!$exception->isOutputable()) {
            return self::notOutputableResponse();
        }

        $result = [
            'message' => $exception->getMessage(),
        ];

        if ($exception->getLocation() instanceof Location) {
            $result['locations'] = [$exception->getLocation()];
        }

        if ($exception->getPath() instanceof Path) {
            $result['path'] = $exception->getPath();
        }

        if (\is_array($exception->getExtensions())) {
            $result['extensions'] = $exception->getExtensions();
        }

        return $result;
    }

    private static function notOutputableResponse() : array
    {
        return [
            'message' => 'Server responded with unknown error.',
        ];
    }

    private function handleAll(\Throwable $exception) : Result
    {
        return new Result(null, [
            $exception instanceof ClientAware
                ? self::serializeError($exception)
                : self::notOutputableResponse(),
        ]);
    }

    private function handleOutputable(\Throwable $exception) : Result
    {
        return $exception instanceof ClientAware && $exception->isOutputable()
            ? new Result(null, [self::serializeError($exception)])
            : throw $exception;
    }

    private function handleClientAware(\Throwable $exception) : Result
    {
        return $exception instanceof ClientAware
            ? new Result(null, [self::serializeError($exception)])
            : throw $exception;
    }

    private function handleNone(\Throwable $exception) : never
    {
        throw $exception;
    }
}
