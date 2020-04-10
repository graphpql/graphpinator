<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Spec;

final class IntrospectionTest extends \PHPUnit\Framework\TestCase
{
    public function typenameDataProvider() : array
    {
        return [
            [
                'query queryName { __typename }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__typename' => 'Query']]),
            ],
            [
                'query queryName { field0 { __typename } }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['__typename' => 'Abc']]]),
            ],
        ];
    }

    /**
     * @dataProvider typenameDataProvider
     */
    public function testTypename(string $request, \Infinityloop\Utils\Json $result) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request, \Infinityloop\Utils\Json::fromArray([]))),
        );
    }

    public function schemaDataProvider() : array
    {
        return [
            [
                'query queryName { __typename }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__typename' => 'Query']]),
            ],
            [
                'query queryName { field0 { __typename } }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['__typename' => 'Abc']]]),
            ],
        ];
    }

    /**
     * @dataProvider schemaDataProvider
     */
    public function testSchema(string $request, \Infinityloop\Utils\Json $result) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request, \Infinityloop\Utils\Json::fromArray([]))),
        );
    }

    public function typeDataProvider() : array
    {
        return [
            [
                'query queryName { __type(name: "Abc") { kind name description } }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__type' => ['kind' => 'OBJECT', 'name' => 'Abc', 'description' => 'Test Abc description']]]),
            ],
        ];
    }

    /**
     * @dataProvider typeDataProvider
     */
    public function testType(string $request, \Infinityloop\Utils\Json $result) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request, \Infinityloop\Utils\Json::fromArray([]))),
        );
    }
}
