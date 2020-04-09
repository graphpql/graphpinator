<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Spec;

final class FragmentTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                'query queryName { field0 { field1 { ... on Abc { name } } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => []]]]),
            ],
            [
                'query queryName { field0 { field1 { ... on Xyz { name } } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName { ... on Query { field0 { field1 { ... on Xyz { name } } } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName { ... namedFragment } fragment namedFragment on Query { field0 { field1 { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName { ... namedFragment } fragment namedFragment on Query { field0 { field1 { ... on Xyz { name } } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple(string $request, \Infinityloop\Utils\Json $variables, \Infinityloop\Utils\Json $result) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());

        self::assertSame(
            $result->toString(),
            \json_encode($graphpinator->runQuery($request, $variables)),
        );
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                'query queryName { ...namedFragment }',
                \Infinityloop\Utils\Json::fromArray([]),
            ],
            [
                'query queryName { ...namedFragment ...namedFragment } fragment namedFragment on Query { field0 {} }',
                \Infinityloop\Utils\Json::fromArray([]),
            ],
            [
                'query queryName { ...namedFragment } fragment namedFragment on Query { ...cycleFragment field0 } fragment cycleFragment on Query { ...namedFragment }',
                \Infinityloop\Utils\Json::fromArray([]),
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalid(string $request, \Infinityloop\Utils\Json $variables) : void
    {
        $this->expectException(\Exception::class);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request, $variables);
    }
}
