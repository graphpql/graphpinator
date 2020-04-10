<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Spec;

final class IntrospectionTest extends \PHPUnit\Framework\TestCase
{
    public function typenameDataProvider() : array
    {
        return [
            [
                '{ __typename }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__typename' => 'Query']]),
            ],
            [
                '{ field0 { __typename } }',
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
                '{ __typename }',
                \Infinityloop\Utils\Json::fromArray(['data' => ['__typename' => 'Query']]),
            ],
            [
                '{ field0 { __typename } }',
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
                '{ __type(name: "Abc") { kind name description fields{name} interfaces{name} possibleTypes{name} inputFields{name} enumValues{name} ofType{name} } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'kind' => 'OBJECT',
                        'name' => 'Abc',
                        'description' => 'Test Abc description',
                        'fields' => [['name' => 'field1']],
                        'interfaces' => [],
                        'possibleTypes' => null,
                        'inputFields' => null,
                        'enumValues' => null,
                        'ofType' => null,
                    ]],
                ]),
            ],
            [
                '{ __type(name: "Xyz") { kind name description fields{name} interfaces{name} possibleTypes{name} inputFields{name} enumValues{name} ofType{name} } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'kind' => 'OBJECT',
                        'name' => 'Xyz',
                        'description' => null,
                        'fields' => [['name' => 'name']],
                        'interfaces' => [['name' => 'TestInterface']],
                        'possibleTypes' => null,
                        'inputFields' => null,
                        'enumValues' => null,
                        'ofType' => null,
                    ]],
                ]),
            ],
            [
                '{ __type(name: "TestInterface") { kind name description fields{name} interfaces{name} possibleTypes{name} inputFields{name} enumValues{name} ofType{name} } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'kind' => 'INTERFACE',
                        'name' => 'TestInterface',
                        'description' => null,
                        'fields' => [['name' => 'name']],
                        'interfaces' => [],
                        'possibleTypes' => null,
                        'inputFields' => null,
                        'enumValues' => null,
                        'ofType' => null,
                    ]],
                ]),
            ],
            [
                '{ __type(name: "TestUnion") { kind name description fields{name} interfaces{name} possibleTypes{name} inputFields{name} enumValues{name} ofType{name} } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'kind' => 'UNION',
                        'name' => 'TestUnion',
                        'description' => null,
                        'fields' => null,
                        'interfaces' => null,
                        'possibleTypes' => [['name' => 'Abc'], ['name' => 'Xyz']],
                        'inputFields' => null,
                        'enumValues' => null,
                        'ofType' => null,
                    ]],
                ]),
            ],
            [
                '{ __type(name: "TestInnerInput") { kind name description fields{name} interfaces{name} possibleTypes{name} inputFields{name} enumValues{name} ofType{name} } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'kind' => 'INPUT_OBJECT',
                        'name' => 'TestInnerInput',
                        'description' => null,
                        'fields' => null,
                        'interfaces' => null,
                        'possibleTypes' => null,
                        'inputFields' => [['name' => 'name'], ['name' => 'number'], ['name' => 'bool']],
                        'enumValues' => null,
                        'ofType' => null,
                    ]],
                ]),
            ],
            [
                '{ __type(name: "TestInterface") { fields{name type {kind name ofType {name}} } } }',
                \Infinityloop\Utils\Json::fromArray([
                    'data' => ['__type' => [
                        'fields' => [['name' => 'name', 'type' => ['kind' => 'NON_NULL', 'name' => null, 'ofType' => ['name' => 'String']]]],
                    ]],
                ]),
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
