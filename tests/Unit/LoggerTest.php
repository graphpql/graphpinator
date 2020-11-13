<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit;

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
            ->will($this->returnCallback([$this, 'mockLog']));

        $graphpinator = new \Graphpinator\Graphpinator(
            \Graphpinator\Tests\Spec\TestSchema::getSchema(),
            true,
            null,
            $loggerMock,
        );

        $request = \Graphpinator\Json::fromObject((object) [
            'query' => 'query queryName { fieldArgumentDefaults(inputNumberList: [3, 4]) { fieldName fieldNumber fieldBool } }',
        ]);

        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame(\Psr\Log\LogLevel::DEBUG, $this->logs[0]['level']);
        self::assertSame($request['query'], $this->logs[0]['message']);
    }

    public function testInfo() : void
    {
        $this->logs = [];

        $loggerMock = $this->createPartialMock(\Psr\Log\AbstractLogger::class, ['log']);
        $loggerMock->expects($this->any())
            ->method('log')
            ->will($this->returnCallback([$this, 'mockLog']));

        $graphpinator = new \Graphpinator\Graphpinator(
            \Graphpinator\Tests\Spec\TestSchema::getSchema(),
            true,
            null,
            $loggerMock,
        );

        $request = \Graphpinator\Json::fromObject((object) [
            'query' => 'query queryName ($var: Int) field',
        ]);
        $expected = 'Expected selection set, got "name".';

        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame(\Psr\Log\LogLevel::DEBUG, $this->logs[0]['level']);
        self::assertSame($request['query'], $this->logs[0]['message']);
        self::assertSame(\Psr\Log\LogLevel::INFO, $this->logs[1]['level']);
        self::assertSame($expected, $this->logs[1]['message']);
    }

    public function testEmergency() : void
    {
        $this->logs = [];

        $loggerMock = $this->createPartialMock(\Psr\Log\AbstractLogger::class, ['log']);
        $loggerMock->expects($this->any())
            ->method('log')
            ->will($this->returnCallback([$this, 'mockLog']));

        $graphpinator = new \Graphpinator\Graphpinator(
            \Graphpinator\Tests\Spec\TestSchema::getSchema(),
            true,
            null,
            $loggerMock,
        );

        $request = \Graphpinator\Json::fromObject((object) [
            'query' => 'query queryName { fieldAbc { fieldXyz(arg1: 123, arg1: 456) { name } } }',
        ]);
        $expected = 'Duplicated item';

        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame(\Psr\Log\LogLevel::DEBUG, $this->logs[0]['level']);
        self::assertSame($request['query'], $this->logs[0]['message']);
        self::assertSame(\Psr\Log\LogLevel::EMERGENCY, $this->logs[1]['level']);
        self::assertSame($expected, $this->logs[1]['message']);
    }
}
