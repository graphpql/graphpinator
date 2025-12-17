<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Request;

use Graphpinator\Request\Exception\InvalidMethod;
use Graphpinator\Request\Exception\InvalidMultipartRequest;
use Graphpinator\Request\Exception\QueryMissing;
use Graphpinator\Request\PsrRequestFactory;
use Graphpinator\Request\Request;
use GuzzleHttp\Psr7\ServerRequest;
use GuzzleHttp\Psr7\Utils;
use PHPUnit\Framework\TestCase;

final class PsrRequestFactoryTest extends TestCase
{
    public function testGetRequestWithQuery() : void
    {
        $serverRequest = (new ServerRequest(
            'GET',
            'http://example.com/graphql?query={field}',
        ))->withQueryParams(['query' => '{field}']);
        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertInstanceOf(Request::class, $request);
        self::assertSame('{field}', $request->query);
    }

    public function testGetRequestWithQueryAndVariables() : void
    {
        $serverRequest = (new ServerRequest(
            'GET',
            'http://example.com/graphql?query={field}&variables={"var":"value"}',
        ))->withQueryParams(['query' => '{field}', 'variables' => '{"var":"value"}']);
        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertInstanceOf(Request::class, $request);
        self::assertSame('{field}', $request->query);
        self::assertIsObject($request->variables);
        self::assertEquals('value', $request->variables->var);
    }

    public function testGetRequestWithOperationName() : void
    {
        $serverRequest = (new ServerRequest(
            'GET',
            'http://example.com/graphql?query={field}&operationName=TestOp',
        ))->withQueryParams(['query' => '{field}', 'operationName' => 'TestOp']);
        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertSame('TestOp', $request->operationName);
    }

    public function testPostRequestWithApplicationJson() : void
    {
        $body = Utils::streamFor('{"query":"{field}","variables":{"var":"value"}}');
        $serverRequest = new ServerRequest(
            'POST',
            'http://example.com/graphql',
            ['Content-Type' => 'application/json'],
            $body,
        );
        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertSame('{field}', $request->query);
        self::assertEquals('value', $request->variables->var);
    }

    public function testPostRequestWithApplicationGraphql() : void
    {
        $body = Utils::streamFor('{field}');
        $serverRequest = new ServerRequest(
            'POST',
            'http://example.com/graphql',
            ['Content-Type' => 'application/graphql'],
            $body,
        );
        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertSame('{field}', $request->query);
    }

    public function testPostRequestWithoutContentType() : void
    {
        $serverRequest = (new ServerRequest(
            'POST',
            'http://example.com/graphql?query={field}',
        ))->withQueryParams(['query' => '{field}']);
        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertInstanceOf(Request::class, $request);
        self::assertSame('{field}', $request->query);
    }

    public function testInvalidMethod() : void
    {
        $this->expectException(InvalidMethod::class);

        $serverRequest = new ServerRequest('PUT', 'http://example.com/graphql');
        $factory = new PsrRequestFactory($serverRequest);
        $factory->create();
    }

    public function testDeleteMethod() : void
    {
        $this->expectException(InvalidMethod::class);

        $serverRequest = new ServerRequest('DELETE', 'http://example.com/graphql');
        $factory = new PsrRequestFactory($serverRequest);
        $factory->create();
    }

    public function testPatchMethod() : void
    {
        $this->expectException(InvalidMethod::class);

        $serverRequest = new ServerRequest('PATCH', 'http://example.com/graphql');
        $factory = new PsrRequestFactory($serverRequest);
        $factory->create();
    }

    public function testMultipartFormDataWithOperations() : void
    {
        $parsedBody = [
            'operations' => '{"query":"{field}"}',
        ];
        $serverRequest = (new ServerRequest(
            'POST',
            'http://example.com/graphql',
            ['Content-Type' => 'multipart/form-data; boundary=----WebKitFormBoundary'],
        ))->withParsedBody($parsedBody);

        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertSame('{field}', $request->query);
    }

    public function testMultipartFormDataWithoutOperations() : void
    {
        $this->expectException(InvalidMultipartRequest::class);

        $parsedBody = ['other' => 'data'];
        $serverRequest = (new ServerRequest(
            'POST',
            'http://example.com/graphql',
            ['Content-Type' => 'multipart/form-data'],
        ))->withParsedBody($parsedBody);

        $factory = new PsrRequestFactory($serverRequest);
        $factory->create();
    }

    public function testMultipartFormDataGetRequest() : void
    {
        $this->expectException(InvalidMultipartRequest::class);

        $serverRequest = new ServerRequest(
            'GET',
            'http://example.com/graphql',
            ['Content-Type' => 'multipart/form-data'],
        );

        $factory = new PsrRequestFactory($serverRequest);
        $factory->create();
    }

    public function testStrictModeTrue() : void
    {
        $body = Utils::streamFor('{"query":"{field}"}');
        $serverRequest = new ServerRequest(
            'POST',
            'http://example.com/graphql',
            ['Content-Type' => 'application/json'],
            $body,
        );
        $factory = new PsrRequestFactory($serverRequest, strict: true);
        $request = $factory->create();

        self::assertInstanceOf(Request::class, $request);
    }

    public function testStrictModeFalse() : void
    {
        $body = Utils::streamFor('{"query":"{field}","customKey":"value"}');
        $serverRequest = new ServerRequest(
            'POST',
            'http://example.com/graphql',
            ['Content-Type' => 'application/json'],
            $body,
        );
        $factory = new PsrRequestFactory($serverRequest, strict: false);
        $request = $factory->create();

        self::assertInstanceOf(Request::class, $request);
    }

    public function testJsonWithAllFields() : void
    {
        $body = Utils::streamFor('{"query":"{field}","variables":{"var":"value"},"operationName":"TestOp"}');
        $serverRequest = new ServerRequest(
            'POST',
            'http://example.com/graphql',
            ['Content-Type' => 'application/json'],
            $body,
        );
        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertSame('{field}', $request->query);
        self::assertEquals('value', $request->variables->var);
        self::assertSame('TestOp', $request->operationName);
    }

    public function testEmptyQueryParams() : void
    {
        $this->expectException(QueryMissing::class);

        $serverRequest = (new ServerRequest(
            'GET',
            'http://example.com/graphql',
        ))->withQueryParams([]);
        $factory = new PsrRequestFactory($serverRequest);
        $factory->create();
    }

    public function testMultipleContentTypeHeaders() : void
    {
        $body = Utils::streamFor('{"query":"{field}"}');
        $serverRequest = new ServerRequest(
            'POST',
            'http://example.com/graphql',
            ['Content-Type' => ['text/plain', 'application/json']],
            $body,
        );
        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertInstanceOf(Request::class, $request);
    }

    public function testContentTypeWithCharset() : void
    {
        $body = Utils::streamFor('{"query":"{field}"}');
        $serverRequest = (new ServerRequest(
            'POST',
            'http://example.com/graphql',
            ['Content-Type' => 'application/json; charset=utf-8'],
            $body,
        ))->withQueryParams(['query' => '{field}']);
        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertInstanceOf(Request::class, $request);
    }

    public function testGraphqlContentTypeWithCharset() : void
    {
        $body = Utils::streamFor('{field}');
        $serverRequest = (new ServerRequest(
            'POST',
            'http://example.com/graphql',
            ['Content-Type' => 'application/graphql; charset=utf-8'],
            $body,
        ))->withQueryParams(['query' => '{field}']);
        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertSame('{field}', $request->query);
    }

    public function testGetWithComplexVariables() : void
    {
        $variables = \json_encode(['var1' => 'value1', 'nested' => ['key' => 'value']]);
        $serverRequest = (new ServerRequest(
            'GET',
            'http://example.com/graphql?query={field}&variables=' . \urlencode($variables),
        ))->withQueryParams(['query' => '{field}', 'variables' => $variables]);
        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertIsObject($request->variables);
        self::assertEquals('value1', $request->variables->var1);
    }

    public function testMultipartWithComplexOperations() : void
    {
        $operations = \json_encode([
            'query' => '{field}',
            'variables' => ['var' => 'value'],
            'operationName' => 'TestOp',
        ]);
        $parsedBody = ['operations' => $operations];
        $serverRequest = (new ServerRequest(
            'POST',
            'http://example.com/graphql',
            ['Content-Type' => 'multipart/form-data; boundary=xyz'],
        ))->withParsedBody($parsedBody);

        $factory = new PsrRequestFactory($serverRequest);
        $request = $factory->create();

        self::assertSame('{field}', $request->query);
        self::assertEquals('value', $request->variables->var);
        self::assertSame('TestOp', $request->operationName);
    }
}
