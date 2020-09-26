<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class SimpleTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldValid { field1 { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldValid' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { aliasName: fieldValid { field1 { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['aliasName' => ['field1' => ['name' => 'Test 123']]]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Graphpinator\Json $request
     * @param \Graphpinator\Json $expected
     */
    public function testSimple(\Graphpinator\Json $request, \Graphpinator\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->runQuery($request);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame($expected['data'], \json_decode(\json_encode($result->getData()), true));
        self::assertNull($result->getErrors());
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Graphpinator\Json $request
     * @param \Graphpinator\Json $expected
     */
    public function testComponents(\Graphpinator\Json $request, \Graphpinator\Json $expected) : void
    {
        $source = new \Graphpinator\Source\StringSource($request['query']);
        $parser = new \Graphpinator\Parser\Parser($source);
        $normalizer = new \Graphpinator\Normalizer\Normalizer(TestSchema::getSchema());
        $resolver = new \Graphpinator\Resolver\Resolver();

        $operationName = $request['operationName']
            ?? null;
        $variables = $request['variables']
            ?? new \stdClass();

        $result = $resolver->resolve($normalizer->normalize($parser->parse()), $operationName, $variables);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame($expected['data'], \json_decode(\json_encode($result->getData()), true));
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldValid { field1 } }',
                ]),
                \Graphpinator\Exception\Resolver\SelectionOnComposite::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldValid { field1 { nonExisting } } }',
                ]),
                //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
                \Exception::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldValid { field1 { name { nonExisting } } } }',
                ]),
                //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
                \Exception::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldInvalidType { } }',
                ]),
                \Graphpinator\Exception\Resolver\FieldResultTypeMismatch::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldInvalidReturn { } }',
                ]),
                \Graphpinator\Exception\Resolver\FieldResultAbstract::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) []),
                \Graphpinator\Exception\Request\QueryMissing::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 123,
                ]),
                \Graphpinator\Exception\Request\QueryNotString::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '',
                    'variables' => 'abc',
                ]),
                \Graphpinator\Exception\Request\VariablesNotObject::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => '',
                    'operationName' => 123,
                ]),
                \Graphpinator\Exception\Request\OperationNameNotString::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
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
     * @param \Graphpinator\Json $request
     * @param string $exception
     */
    public function testInvalid(\Graphpinator\Json $request, string $exception) : void
    {
        $this->expectException($exception);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request);
    }
}
