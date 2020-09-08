<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class VariableTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int) { field0 { field1(arg1: $var1) { name } } }',
                    'variables' => (object) ['var1' => 456],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['field0' => ['field1' => ['name' => 'Test 456']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int) { field0 { field1(arg1: $var1) { name } } }',
                    'variables' => (object) ['var1' => 123],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = 456) { field0 { field1(arg1: $var1) { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['field0' => ['field1' => ['name' => 'Test 456']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = 123) { field0 { field1(arg1: $var1) { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['field0' => ['field1' => ['name' => 'Test 123']]]]),
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

    public function invalidDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = "123") { field0 { field1 { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Exception\Type\InvalidResolvedValue::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = "123") { field0 { field1 { name } } }',
                    'variables' => ['var1' => '123'],
                ]),
                \Graphpinator\Exception\Type\InvalidResolvedValue::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int!) { field0 { field1 { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Exception\Type\ExpectedNotNullValue::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { field0 { field1(arg1: $varNonExistent) { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Exception\Parser\UnknownVariable::class,
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
