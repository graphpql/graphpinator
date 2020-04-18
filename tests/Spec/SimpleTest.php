<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Spec;

final class SimpleTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                'query queryName { field0 { field1 { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                'query queryName { aliasName: field0 { field1 { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['aliasName' => ['field1' => ['name' => 'Test 123']]]]),
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

    /**
     * @dataProvider simpleDataProvider
     */
    public function testComponents(string $request, \Infinityloop\Utils\Json $variables, \Infinityloop\Utils\Json $expected) : void
    {
        $source = new \Graphpinator\Source\StringSource($request);
        $parser = new \Graphpinator\Parser\Parser($source);
        $normalizer = new \Graphpinator\Normalizer\Normalizer(TestSchema::getSchema());
        $resolver = new \Graphpinator\Resolver\Resolver();

        $result = $resolver->resolve($normalizer->normalize($parser->parse()), $variables);

        self::assertSame($expected->toString(), \json_encode($result, JSON_THROW_ON_ERROR, 512),);
        self::assertSame($expected['data'], \json_decode(\json_encode($result->getData()), true));
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                'query queryName { field0 { field1 } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Graphpinator\Exception\Resolver\SelectionOnComposite::class,
            ],
            [
                'query queryName { field0 { field1 { nonExisting } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Exception::class,
            ],
            [
                'query queryName { field0 { field1 { name { nonExisting } } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Exception::class,
            ],
            [
                'query queryName { fieldInvalidType { } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Graphpinator\Exception\Resolver\FieldResultTypeMismatch::class,
            ],
            [
                'query queryName { fieldAbstract { } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Graphpinator\Exception\Resolver\FieldResultAbstract::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalid(string $request, \Infinityloop\Utils\Json $variables, string $exception) : void
    {
        $this->expectException($exception);
        //$this->expectExceptionMessage(\constant($exception . '::MESSAGE'));

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request, $variables);
    }
}
