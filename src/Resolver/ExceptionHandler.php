<?php

declare(strict_types = 1);

namespace Graphpinator\Resolver;

use Graphpinator\Common\Location;
use Graphpinator\Common\Path;
use Graphpinator\Exception\ClientAware;

final readonly class ExceptionHandler
{
    private ErrorHandlingMode $errorHandlingMode;

    public function __construct(
        ErrorHandlingMode|bool $errorHandlingMode,
    )
    {
        $this->errorHandlingMode = $errorHandlingMode instanceof ErrorHandlingMode
            ? $errorHandlingMode
            : ErrorHandlingMode::fromBool($errorHandlingMode);
    }

    public function handle(\Throwable $exception) : Result
    {
        return match ($this->errorHandlingMode) {
            ErrorHandlingMode::ALL => self::handleAll($exception),
            ErrorHandlingMode::OUTPUTABLE => self::handleOutputable($exception),
            ErrorHandlingMode::CLIENT_AWARE => self::handleClientAware($exception),
            ErrorHandlingMode::NONE => self::handleNone($exception),
        };
    }

    private static function handleAll(\Throwable $exception) : Result
    {
        return new Result(null, [
            $exception instanceof ClientAware
                ? self::serializeError($exception)
                : self::notOutputableResponse(),
        ]);
    }

    private static function handleOutputable(\Throwable $exception) : Result
    {
        return $exception instanceof ClientAware && $exception->isOutputable()
            ? new Result(null, [self::serializeError($exception)])
            : throw $exception;
    }

    private static function handleClientAware(\Throwable $exception) : Result
    {
        return $exception instanceof ClientAware
            ? new Result(null, [self::serializeError($exception)])
            : throw $exception;
    }

    private static function handleNone(\Throwable $exception) : never
    {
        throw $exception;
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
}
