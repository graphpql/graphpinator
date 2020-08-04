<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class SimpleTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { aliasName: field0 { field1 { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['aliasName' => ['field1' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testSimple(\Infinityloop\Utils\Json $request, \Infinityloop\Utils\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->runQuery($request);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame($expected['data'], \json_decode(\json_encode($result->getData()), true));
        self::assertNull($result->getErrors());
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testComponents(\Infinityloop\Utils\Json $request, \Infinityloop\Utils\Json $expected) : void
    {
        $source = new \Graphpinator\Source\StringSource($request['query']);
        $parser = new \Graphpinator\Parser\Parser($source);
        $normalizer = new \Graphpinator\Normalizer\Normalizer(TestSchema::getSchema());
        $resolver = new \Graphpinator\Resolver\Resolver();

        $operationName = $request['operationName']
            ?? null;
        $variables = $request['variables']
            ?? [];

        $result = $resolver->resolve($normalizer->normalize($parser->parse()), $operationName, $variables);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame($expected['data'], \json_decode(\json_encode($result->getData()), true));
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 } }',
                ]),
                \Graphpinator\Exception\Resolver\SelectionOnComposite::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { nonExisting } } }',
                ]),
                //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
                \Exception::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1 { name { nonExisting } } } }',
                ]),
                //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
                \Exception::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { fieldInvalidType { } }',
                ]),
                \Graphpinator\Exception\Resolver\FieldResultTypeMismatch::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { fieldAbstract { } }',
                ]),
                \Graphpinator\Exception\Resolver\FieldResultAbstract::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([]),
                \Graphpinator\Exception\Request\QueryMissing::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 123,
                ]),
                \Graphpinator\Exception\Request\QueryNotString::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '',
                    'variables' => 'abc',
                ]),
                \Graphpinator\Exception\Request\VariablesNotArray::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '',
                    'operationName' => 123,
                ]),
                \Graphpinator\Exception\Request\OperationNameNotString::class,
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => '',
                    'operationName' => '',
                    'randomKey' => 'randomVal',
                ]),
                \Graphpinator\Exception\Request\UnknownKey::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param string $exception
     */
    public function testInvalid(\Infinityloop\Utils\Json $request, string $exception) : void
    {
        $this->expectException($exception);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request);
    }
}
