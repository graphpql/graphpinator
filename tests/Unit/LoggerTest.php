<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit;

use Infinityloop\Utils\Json;

final class LoggerTest extends \PHPUnit\Framework\TestCase
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

        $loggerMock = $this->createPartialMock(\Psr\Log\AbstractLogger::class, ['log']);
        $loggerMock->expects($this->any())
            ->method('log')
            ->willReturnCallback([$this, 'mockLog']);

        $graphpinator = new \Graphpinator\Graphpinator(
            \Graphpinator\Tests\Spec\TestSchema::getSchema(),
            true,
            null,
            $loggerMock,
        );

        $request = Json::fromNative((object) [
            'query' => 'query queryName { fieldArgumentDefaults(inputNumberList: [3, 4]) { fieldName fieldNumber fieldBool } }',
        ]);

        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame(\Psr\Log\LogLevel::DEBUG, $this->logs[0]['level']);
        self::assertSame($request['query'], $this->logs[0]['message']);
        self::assertCount(1, $this->logs);
    }

    public function testInfo() : void
    {
        $this->logs = [];

        $loggerMock = $this->createPartialMock(\Psr\Log\AbstractLogger::class, ['log']);
        $loggerMock->expects($this->any())
            ->method('log')
            ->willReturnCallback([$this, 'mockLog']);

        $graphpinator = new \Graphpinator\Graphpinator(
            \Graphpinator\Tests\Spec\TestSchema::getSchema(),
            true,
            null,
            $loggerMock,
        );

        $request = Json::fromNative((object) [
            'query' => 'query queryName ($var: Int) field',
        ]);
        $expected = 'Expected selection set, got "name".';

        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame(\Psr\Log\LogLevel::DEBUG, $this->logs[0]['level']);
        self::assertSame($request['query'], $this->logs[0]['message']);
        self::assertSame(\Psr\Log\LogLevel::INFO, $this->logs[1]['level']);
        self::assertSame($expected, $this->logs[1]['message']);
        self::assertCount(2, $this->logs);
    }

    public function testEmergency() : void
    {
        $this->logs = [];

        $loggerMock = $this->createPartialMock(\Psr\Log\AbstractLogger::class, ['log']);
        $loggerMock->expects($this->any())
            ->method('log')
            ->willReturnCallback([$this, 'mockLog']);

        $graphpinator = new \Graphpinator\Graphpinator(
            \Graphpinator\Tests\Spec\TestSchema::getSchema(),
            true,
            null,
            $loggerMock,
        );

        $request = Json::fromNative((object) [
            'query' => 'query queryName { fieldThrow { fieldXyz { name } } }',
        ]);
        $expected = 'Random exception';

        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame(\Psr\Log\LogLevel::DEBUG, $this->logs[0]['level']);
        self::assertSame($request['query'], $this->logs[0]['message']);
        self::assertSame(\Psr\Log\LogLevel::EMERGENCY, $this->logs[1]['level']);
        self::assertSame($expected, $this->logs[1]['message']);
        self::assertCount(2, $this->logs);
    }

    public function testSetLogger() : void
    {
        $this->logs = [];

        $loggerMock = $this->createPartialMock(\Psr\Log\AbstractLogger::class, ['log']);
        $loggerMock->expects($this->any())
            ->method('log')
            ->willReturnCallback([$this, 'mockLog']);

        $graphpinator = new \Graphpinator\Graphpinator(
            \Graphpinator\Tests\Spec\TestSchema::getSchema(),
            true,
            null,
            $loggerMock,
        );
        $request = Json::fromNative((object) [
            'query' => 'query queryName { fieldArgumentDefaults(inputNumberList: [3, 4]) { fieldName fieldNumber fieldBool } }',
        ]);

        $graphpinator->setLogger(new \Psr\Log\NullLogger());
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertCount(0, $this->logs);

        $graphpinator->setLogger($loggerMock);
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame(\Psr\Log\LogLevel::DEBUG, $this->logs[0]['level']);
        self::assertSame($request['query'], $this->logs[0]['message']);
        self::assertCount(1, $this->logs);
    }
}
