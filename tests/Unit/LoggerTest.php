<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit;

use Graphpinator\Graphpinator;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\Tests\Spec\TestSchema;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

final class LoggerTest extends TestCase
{
    protected array $logs;

    public function mockLog(string $level, string $message, array $context) : void
    {
        $this->logs[] = [
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ];
    }

    public function testDebug() : void
    {
        $this->logs = [];

        $loggerMock = $this->createPartialMock(AbstractLogger::class, ['log']);
        $loggerMock->expects($this->once())
            ->method('log')
            ->willReturnCallback([$this, 'mockLog']);

        $graphpinator = new Graphpinator(
            TestSchema::getSchema(),
            true,
            logger: $loggerMock,
        );

        $request = Json::fromNative((object) [
            'query' => 'query queryName { fieldArgumentDefaults(inputNumberList: [3, 4]) { fieldName fieldNumber fieldBool } }',
        ]);

        $graphpinator->run(new JsonRequestFactory($request));

        self::assertCount(1, $this->logs);
        self::assertSame(LogLevel::DEBUG, $this->logs[0]['level']);
        self::assertSame($request['query'], $this->logs[0]['message']);
    }

    public function testInfo() : void
    {
        $this->logs = [];

        $loggerMock = $this->createPartialMock(AbstractLogger::class, ['log']);
        $loggerMock->expects($this->exactly(2))
            ->method('log')
            ->willReturnCallback([$this, 'mockLog']);

        $graphpinator = new Graphpinator(
            TestSchema::getSchema(),
            true,
            logger: $loggerMock,
        );

        $request = Json::fromNative((object) [
            'query' => 'query queryName ($var: Int) field',
        ]);
        $expected = 'Expected selection set, got "name".';

        $graphpinator->run(new JsonRequestFactory($request));

        self::assertCount(2, $this->logs);
        self::assertSame(LogLevel::DEBUG, $this->logs[0]['level']);
        self::assertSame($request['query'], $this->logs[0]['message']);
        self::assertSame(LogLevel::INFO, $this->logs[1]['level']);
        self::assertStringStartsWith($expected, $this->logs[1]['message'] . ' in ');
    }

    public function testEmergency() : void
    {
        $this->logs = [];

        $loggerMock = $this->createPartialMock(AbstractLogger::class, ['log']);
        $loggerMock->expects($this->exactly(2))
            ->method('log')
            ->willReturnCallback([$this, 'mockLog']);

        $graphpinator = new Graphpinator(
            TestSchema::getSchema(),
            true,
            logger: $loggerMock,
        );

        $request = Json::fromNative((object) [
            'query' => 'query queryName { fieldThrow { fieldXyz { name } } }',
        ]);
        $expected = 'Random exception';

        $graphpinator->run(new JsonRequestFactory($request));

        self::assertCount(2, $this->logs);
        self::assertSame(LogLevel::DEBUG, $this->logs[0]['level']);
        self::assertSame($request['query'], $this->logs[0]['message']);
        self::assertSame(LogLevel::EMERGENCY, $this->logs[1]['level']);
        self::assertStringStartsWith($expected, $this->logs[1]['message'] . ' in ');
    }

    public function testSetLogger() : void
    {
        $this->logs = [];

        $loggerMock = $this->createPartialMock(AbstractLogger::class, ['log']);
        $loggerMock->expects($this->once())
            ->method('log')
            ->willReturnCallback([$this, 'mockLog']);

        $graphpinator = new Graphpinator(
            TestSchema::getSchema(),
            true,
            logger: $loggerMock,
        );
        $request = Json::fromNative((object) [
            'query' => 'query queryName { fieldArgumentDefaults(inputNumberList: [3, 4]) { fieldName fieldNumber fieldBool } }',
        ]);

        $graphpinator->setLogger(new NullLogger());
        $graphpinator->run(new JsonRequestFactory($request));

        self::assertCount(0, $this->logs);

        $graphpinator->setLogger($loggerMock);
        $graphpinator->run(new JsonRequestFactory($request));

        self::assertCount(1, $this->logs);
        self::assertSame(LogLevel::DEBUG, $this->logs[0]['level']);
        self::assertSame($request['query'], $this->logs[0]['message']);
    }
}
