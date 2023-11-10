<?php

declare(strict_types = 1);

namespace Graphpinator;

final class Graphpinator implements \Psr\Log\LoggerAwareInterface
{
    /**
     * Whether Graphpinator should perform schema integrity checks. Disable in production to avoid unnecessary overhead.
     */
    public static bool $validateSchema = true;
    private \Graphpinator\Parser\Parser $parser;
    private \Graphpinator\Normalizer\Normalizer $normalizer;
    private \Graphpinator\Normalizer\Finalizer $finalizer;
    private \Graphpinator\Resolver\Resolver $resolver;

    public function __construct(
        \Graphpinator\Typesystem\Schema $schema,
        private bool $catchExceptions = false,
        private \Graphpinator\Module\ModuleSet $modules = new \Graphpinator\Module\ModuleSet([]),
        private \Psr\Log\LoggerInterface $logger = new \Psr\Log\NullLogger(),
    )
    {
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
                $exception instanceof \Graphpinator\Exception\ClientAware
                    ? self::serializeError($exception)
                    : self::notOutputableResponse(),
            ]);
        }
    }

    public function setLogger(\Psr\Log\LoggerInterface $logger) : void
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
                ? \Psr\Log\LogLevel::INFO
                : \Psr\Log\LogLevel::ERROR;
        }

        return \Psr\Log\LogLevel::EMERGENCY;
    }

    private static function serializeError(\Graphpinator\Exception\ClientAware $exception) : array
    {
        if (!$exception->isOutputable()) {
            return self::notOutputableResponse();
        }

        $result = [
            'message' => $exception->getMessage(),
        ];

        if ($exception->getLocation() instanceof \Graphpinator\Common\Location) {
            $result['locations'] = [$exception->getLocation()];
        }

        if ($exception->getPath() instanceof \Graphpinator\Common\Path) {
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
}
