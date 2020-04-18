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
            [
                'query queryName { ... namedFragment } fragment innerFragment on Xyz { name } fragment namedFragment on Query { field0 { field1 { ... innerFragment } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple(string $request, \Infinityloop\Utils\Json $variables, \Infinityloop\Utils\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->runQuery($request, $variables);

        self::assertSame($expected->toString(), \json_encode($result, JSON_THROW_ON_ERROR, 512),);
        self::assertSame($expected['data'], \json_decode(\json_encode($result->getData()), true));
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                'query queryName { ...namedFragment }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Graphpinator\Exception\Normalizer\UnknownFragment::class,
            ],
            [
                'query queryName { ...namedFragment ...namedFragment } fragment namedFragment on Query { field0 {} }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Graphpinator\Exception\Resolver\DuplicateField::class,
            ],
            [
                'query queryName { ...namedFragment } fragment namedFragment on Query { ...cycleFragment field0 } fragment cycleFragment on Query { ...namedFragment }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Graphpinator\Exception\Normalizer\FragmentCycle::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalid(string $request, \Infinityloop\Utils\Json $variables, string $exception) : void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage(\constant($exception . '::MESSAGE'));

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request, $variables);
    }
}
