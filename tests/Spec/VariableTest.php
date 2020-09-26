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
                    'query' => 'query queryName ($var1: Int) { fieldValid { field1(arg1: $var1) { name } } }',
                    'variables' => (object) ['var1' => 456],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldValid' => ['field1' => ['name' => 'Test 456']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int) { fieldValid { field1(arg1: $var1) { name } } }',
                    'variables' => (object) ['var1' => 123],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldValid' => ['field1' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = 456) { fieldValid { field1(arg1: $var1) { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldValid' => ['field1' => ['name' => 'Test 456']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = 123) { fieldValid { field1(arg1: $var1) { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldValid' => ['field1' => ['name' => 'Test 123']]]]),
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
                    'query' => 'query queryName ($var1: Int = "123") { fieldValid { field1 { name } } }',
                    'variables' => (object) [],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = "123") { fieldValid { field1 { name } } }',
                    'variables' => ['var1' => '123'],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int!) { fieldValid { field1 { name } } }',
                    'variables' => (object) [],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldValid { field1(arg1: $varNonExistent) { name } } }',
                    'variables' => (object) [],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Graphpinator\Json $request
     */
    public function testInvalid(\Graphpinator\Json $request) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request);
    }
}
