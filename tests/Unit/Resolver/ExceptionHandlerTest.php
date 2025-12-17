<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Resolver;

use Graphpinator\Common\Location;
use Graphpinator\Common\Path;
use Graphpinator\Exception\ClientAware;
use Graphpinator\Resolver\ErrorHandlingMode;
use Graphpinator\Resolver\ExceptionHandler;
use Graphpinator\Resolver\Result;
use PHPUnit\Framework\TestCase;

final class ExceptionHandlerTest extends TestCase
{
    public function testAllModeWithClientAwareOutputable() : void
    {
        $exception = new class ('Test message') extends \Exception implements ClientAware {
            public function isOutputable() : bool
            {
                return true;
            }

            public function getLocation() : ?Location
            {
                return null;
            }

            public function getPath() : ?Path
            {
                return null;
            }

            public function getExtensions() : ?array
            {
                return null;
            }
        };

        $handler = new ExceptionHandler(ErrorHandlingMode::ALL);
        $result = $handler->handle($exception);

        self::assertInstanceOf(Result::class, $result);
        self::assertNull($result->data);
        self::assertIsArray($result->errors);
        self::assertCount(1, $result->errors);
        self::assertSame('Test message', $result->errors[0]['message']);
    }

    public function testAllModeWithClientAwareNotOutputable() : void
    {
        $exception = new class ('Test message') extends \Exception implements ClientAware {
            public function isOutputable() : bool
            {
                return false;
            }

            public function getLocation() : ?Location
            {
                return null;
            }

            public function getPath() : ?Path
            {
                return null;
            }

            public function getExtensions() : ?array
            {
                return null;
            }
        };

        $handler = new ExceptionHandler(ErrorHandlingMode::ALL);
        $result = $handler->handle($exception);

        self::assertInstanceOf(Result::class, $result);
        self::assertIsArray($result->errors);
        self::assertCount(1, $result->errors);
        self::assertSame('Server responded with unknown error.', $result->errors[0]['message']);
    }

    public function testAllModeWithUnknownException() : void
    {
        $exception = new \RuntimeException('Unknown error');
        $handler = new ExceptionHandler(ErrorHandlingMode::ALL);
        $result = $handler->handle($exception);

        self::assertInstanceOf(Result::class, $result);
        self::assertIsArray($result->errors);
        self::assertCount(1, $result->errors);
        self::assertSame('Server responded with unknown error.', $result->errors[0]['message']);
    }

    public function testOutputableModeWithOutputableException() : void
    {
        $exception = new class ('Test message') extends \Exception implements ClientAware {
            public function isOutputable() : bool
            {
                return true;
            }

            public function getLocation() : ?Location
            {
                return null;
            }

            public function getPath() : ?Path
            {
                return null;
            }

            public function getExtensions() : ?array
            {
                return null;
            }
        };

        $handler = new ExceptionHandler(ErrorHandlingMode::OUTPUTABLE);
        $result = $handler->handle($exception);

        self::assertInstanceOf(Result::class, $result);
        self::assertSame('Test message', $result->errors[0]['message']);
    }

    public function testOutputableModeWithNotOutputableException() : void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Not outputable');

        $exception = new class ('Not outputable') extends \RuntimeException implements ClientAware {
            public function isOutputable() : bool
            {
                return false;
            }

            public function getLocation() : ?Location
            {
                return null;
            }

            public function getPath() : ?Path
            {
                return null;
            }

            public function getExtensions() : ?array
            {
                return null;
            }
        };

        $handler = new ExceptionHandler(ErrorHandlingMode::OUTPUTABLE);
        $handler->handle($exception);
    }

    public function testOutputableModeWithUnknownException() : void
    {
        $this->expectException(\RuntimeException::class);

        $exception = new \RuntimeException('Unknown error');
        $handler = new ExceptionHandler(ErrorHandlingMode::OUTPUTABLE);
        $handler->handle($exception);
    }

    public function testClientAwareModeWithClientAware() : void
    {
        $exception = new class ('Client aware message') extends \RuntimeException implements ClientAware {
            public function isOutputable() : bool
            {
                return true;
            }

            public function getLocation() : ?Location
            {
                return null;
            }

            public function getPath() : ?Path
            {
                return null;
            }

            public function getExtensions() : ?array
            {
                return null;
            }
        };

        $handler = new ExceptionHandler(ErrorHandlingMode::CLIENT_AWARE);
        $result = $handler->handle($exception);

        self::assertInstanceOf(Result::class, $result);
        self::assertSame('Client aware message', $result->errors[0]['message']);
    }

    public function testClientAwareModeWithUnknownException() : void
    {
        $this->expectException(\RuntimeException::class);

        $exception = new \RuntimeException('Unknown error');
        $handler = new ExceptionHandler(ErrorHandlingMode::CLIENT_AWARE);
        $handler->handle($exception);
    }

    public function testNoneModeAlwaysThrows() : void
    {
        $this->expectException(\RuntimeException::class);

        $exception = new \RuntimeException('Test error');
        $handler = new ExceptionHandler(ErrorHandlingMode::NONE);
        $handler->handle($exception);
    }

    public function testNoneModeThrowsClientAware() : void
    {
        $this->expectException(\RuntimeException::class);

        $exception = new class ('Test') extends \RuntimeException implements ClientAware {
            public function isOutputable() : bool
            {
                return true;
            }

            public function getLocation() : ?Location
            {
                return null;
            }

            public function getPath() : ?Path
            {
                return null;
            }

            public function getExtensions() : ?array
            {
                return null;
            }
        };

        $handler = new ExceptionHandler(ErrorHandlingMode::NONE);
        $handler->handle($exception);
    }

    public function testBoolConstructorTrue() : void
    {
        $exception = new \RuntimeException('Test');
        $handler = new ExceptionHandler(true);
        $result = $handler->handle($exception);

        self::assertInstanceOf(Result::class, $result);
        self::assertSame('Server responded with unknown error.', $result->errors[0]['message']);
    }

    public function testBoolConstructorFalse() : void
    {
        $this->expectException(\RuntimeException::class);

        $exception = new \RuntimeException('Test');
        $handler = new ExceptionHandler(false);
        $handler->handle($exception);
    }

    public function testSerializeWithLocation() : void
    {
        $location = new Location(10, 5);
        $exception = new class ('Test message', $location) extends \Exception implements ClientAware {
            public function __construct(
                string $message,
                private Location $location,
            )
            {
                parent::__construct($message);
            }

            public function isOutputable() : bool
            {
                return true;
            }

            public function getLocation() : ?Location
            {
                return $this->location;
            }

            public function getPath() : ?Path
            {
                return null;
            }

            public function getExtensions() : ?array
            {
                return null;
            }
        };

        $handler = new ExceptionHandler(ErrorHandlingMode::ALL);
        $result = $handler->handle($exception);

        self::assertArrayHasKey('locations', $result->errors[0]);
        self::assertIsArray($result->errors[0]['locations']);
        self::assertInstanceOf(Location::class, $result->errors[0]['locations'][0]);
    }

    public function testSerializeWithPath() : void
    {
        $path = new Path();
        $path->add('field');
        $path->add('subfield');

        $exception = new class ('Test message', $path) extends \Exception implements ClientAware {
            public function __construct(
                string $message,
                private Path $path,
            )
            {
                parent::__construct($message);
            }

            public function isOutputable() : bool
            {
                return true;
            }

            public function getLocation() : ?Location
            {
                return null;
            }

            public function getPath() : ?Path
            {
                return $this->path;
            }

            public function getExtensions() : ?array
            {
                return null;
            }
        };

        $handler = new ExceptionHandler(ErrorHandlingMode::ALL);
        $result = $handler->handle($exception);

        self::assertArrayHasKey('path', $result->errors[0]);
        self::assertInstanceOf(Path::class, $result->errors[0]['path']);
    }

    public function testSerializeWithExtensions() : void
    {
        $extensions = ['code' => 'CUSTOM_ERROR', 'detail' => 'Additional info'];
        $exception = new class ('Test message', $extensions) extends \Exception implements ClientAware {
            public function __construct(
                string $message,
                private array $extensions,
            )
            {
                parent::__construct($message);
            }

            public function isOutputable() : bool
            {
                return true;
            }

            public function getLocation() : ?Location
            {
                return null;
            }

            public function getPath() : ?Path
            {
                return null;
            }

            public function getExtensions() : ?array
            {
                return $this->extensions;
            }
        };

        $handler = new ExceptionHandler(ErrorHandlingMode::ALL);
        $result = $handler->handle($exception);

        self::assertArrayHasKey('extensions', $result->errors[0]);
        self::assertSame('CUSTOM_ERROR', $result->errors[0]['extensions']['code']);
        self::assertSame('Additional info', $result->errors[0]['extensions']['detail']);
    }

    public function testSerializeWithAllFields() : void
    {
        $location = new Location(15, 20);
        $path = new Path();
        $path->add('query');
        $extensions = ['errorCode' => 'ERR_001'];

        $exception = new class ('Complete error', $location, $path, $extensions) extends \Exception implements ClientAware {
            public function __construct(
                string $message,
                private Location $location,
                private Path $path,
                private array $extensions,
            )
            {
                parent::__construct($message);
            }

            public function isOutputable() : bool
            {
                return true;
            }

            public function getLocation() : ?Location
            {
                return $this->location;
            }

            public function getPath() : ?Path
            {
                return $this->path;
            }

            public function getExtensions() : ?array
            {
                return $this->extensions;
            }
        };

        $handler = new ExceptionHandler(ErrorHandlingMode::ALL);
        $result = $handler->handle($exception);

        self::assertSame('Complete error', $result->errors[0]['message']);
        self::assertArrayHasKey('locations', $result->errors[0]);
        self::assertArrayHasKey('path', $result->errors[0]);
        self::assertArrayHasKey('extensions', $result->errors[0]);
    }

    public function testClientAwareNotOutputableInAllMode() : void
    {
        $exception = new class ('Internal error') extends \Exception implements ClientAware {
            public function isOutputable() : bool
            {
                return false;
            }

            public function getLocation() : ?Location
            {
                return null;
            }

            public function getPath() : ?Path
            {
                return null;
            }

            public function getExtensions() : ?array
            {
                return ['internalCode' => 500];
            }
        };

        $handler = new ExceptionHandler(ErrorHandlingMode::ALL);
        $result = $handler->handle($exception);

        self::assertSame('Server responded with unknown error.', $result->errors[0]['message']);
        self::assertArrayNotHasKey('extensions', $result->errors[0]);
    }

    public function testErrorHandlingModeFromBool() : void
    {
        self::assertSame(ErrorHandlingMode::ALL, ErrorHandlingMode::fromBool(true));
        self::assertSame(ErrorHandlingMode::NONE, ErrorHandlingMode::fromBool(false));
    }
}
