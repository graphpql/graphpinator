<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Request;

use Graphpinator\Request\Exception\OperationNameNotString;
use Graphpinator\Request\Exception\QueryMissing;
use Graphpinator\Request\Exception\QueryNotString;
use Graphpinator\Request\Exception\UnknownKey;
use Graphpinator\Request\Exception\VariablesNotObject;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\Request\Request;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class JsonRequestFactoryTest extends TestCase
{
    public function testSimpleQuery() : void
    {
        $json = Json::fromNative((object) [
            'query' => '{ field }',
        ]);
        $factory = new JsonRequestFactory($json);
        $request = $factory->create();

        self::assertInstanceOf(Request::class, $request);
        self::assertSame('{ field }', $request->query);
        self::assertEquals(new \stdClass(), $request->variables);
        self::assertNull($request->operationName);
    }

    public function testQueryWithVariables() : void
    {
        $variables = (object) ['var1' => 'value1', 'var2' => 123];
        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'variables' => $variables,
        ]);
        $factory = new JsonRequestFactory($json);
        $request = $factory->create();

        self::assertSame('{ field }', $request->query);
        self::assertEquals($variables, $request->variables);
        self::assertNull($request->operationName);
    }

    public function testQueryWithOperationName() : void
    {
        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'operationName' => 'TestOperation',
        ]);
        $factory = new JsonRequestFactory($json);
        $request = $factory->create();

        self::assertSame('{ field }', $request->query);
        self::assertSame('TestOperation', $request->operationName);
    }

    public function testFullRequest() : void
    {
        $variables = (object) ['var1' => 'value1'];
        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'variables' => $variables,
            'operationName' => 'TestOp',
        ]);
        $factory = new JsonRequestFactory($json);
        $request = $factory->create();

        self::assertSame('{ field }', $request->query);
        self::assertEquals($variables, $request->variables);
        self::assertSame('TestOp', $request->operationName);
    }

    public function testFromStringMethod() : void
    {
        $jsonString = '{"query":"{ field }","variables":{"var":"value"}}';
        $factory = JsonRequestFactory::fromString($jsonString);
        $request = $factory->create();

        self::assertInstanceOf(Request::class, $request);
        self::assertSame('{ field }', $request->query);
    }

    public function testQueryMissing() : void
    {
        $this->expectException(QueryMissing::class);

        $json = Json::fromNative((object) [
            'variables' => new \stdClass(),
        ]);
        $factory = new JsonRequestFactory($json);
        $factory->create();
    }

    public function testQueryNotString() : void
    {
        $this->expectException(QueryNotString::class);

        $json = Json::fromNative((object) [
            'query' => 123,
        ]);
        $factory = new JsonRequestFactory($json);
        $factory->create();
    }

    public function testQueryNull() : void
    {
        $this->expectException(QueryNotString::class);

        $json = Json::fromNative((object) [
            'query' => null,
        ]);
        $factory = new JsonRequestFactory($json);
        $factory->create();
    }

    public function testVariablesNotObject() : void
    {
        $this->expectException(VariablesNotObject::class);

        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'variables' => 'not an object',
        ]);
        $factory = new JsonRequestFactory($json);
        $factory->create();
    }

    public function testVariablesArray() : void
    {
        $this->expectException(VariablesNotObject::class);

        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'variables' => ['var' => 'value'],
        ]);
        $factory = new JsonRequestFactory($json);
        $factory->create();
    }

    public function testVariablesNull() : void
    {
        $this->expectException(VariablesNotObject::class);

        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'variables' => null,
        ]);
        $factory = new JsonRequestFactory($json);
        $factory->create();
    }

    public function testOperationNameNotString() : void
    {
        $this->expectException(OperationNameNotString::class);

        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'operationName' => 123,
        ]);
        $factory = new JsonRequestFactory($json);
        $factory->create();
    }

    public function testOperationNameNull() : void
    {
        $this->expectException(OperationNameNotString::class);

        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'operationName' => null,
        ]);
        $factory = new JsonRequestFactory($json);
        $factory->create();
    }

    public function testUnknownKeyInStrictMode() : void
    {
        $this->expectException(UnknownKey::class);

        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'unknownKey' => 'value',
        ]);
        $factory = new JsonRequestFactory($json, strict: true);
        $factory->create();
    }

    public function testUnknownKeyInNonStrictMode() : void
    {
        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'unknownKey' => 'value',
            'requestId' => '12345',
        ]);
        $factory = new JsonRequestFactory($json, strict: false);
        $request = $factory->create();

        self::assertInstanceOf(Request::class, $request);
        self::assertSame('{ field }', $request->query);
    }

    public function testMultipleUnknownKeysInStrictMode() : void
    {
        $this->expectException(UnknownKey::class);

        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'key1' => 'value1',
            'key2' => 'value2',
        ]);
        $factory = new JsonRequestFactory($json, strict: true);
        $factory->create();
    }

    public function testEmptyVariables() : void
    {
        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'variables' => new \stdClass(),
        ]);
        $factory = new JsonRequestFactory($json);
        $request = $factory->create();

        self::assertEquals(new \stdClass(), $request->variables);
    }

    public function testComplexVariables() : void
    {
        $variables = (object) [
            'string' => 'value',
            'int' => 123,
            'float' => 45.67,
            'bool' => true,
            'null' => null,
            'nested' => (object) ['key' => 'value'],
            'array' => [1, 2, 3],
        ];
        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'variables' => $variables,
        ]);
        $factory = new JsonRequestFactory($json);
        $request = $factory->create();

        self::assertEquals($variables, $request->variables);
    }

    public function testStrictModeDefaultTrue() : void
    {
        $this->expectException(UnknownKey::class);

        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'unknown' => 'value',
        ]);
        $factory = new JsonRequestFactory($json);
        $factory->create();
    }

    public function testAllValidKeysInStrictMode() : void
    {
        $json = Json::fromNative((object) [
            'query' => '{ field }',
            'variables' => new \stdClass(),
            'operationName' => 'Op',
        ]);
        $factory = new JsonRequestFactory($json, strict: true);
        $request = $factory->create();

        self::assertInstanceOf(Request::class, $request);
    }
}
