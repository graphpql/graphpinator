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
                    'query' => 'query queryName ($var1: Int) { fieldAbc { fieldXyz(arg1: $var1) { name } } }',
                    'variables' => (object) ['var1' => 456],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 456']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int) { fieldAbc { fieldXyz(arg1: $var1) { name } } }',
                    'variables' => (object) ['var1' => 123],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = 456) { fieldAbc { fieldXyz(arg1: $var1) { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 456']]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = 123) { fieldAbc { fieldXyz(arg1: $var1) { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Json::fromObject((object) ['data' => ['fieldAbc' => ['fieldXyz' => ['name' => 'Test 123']]]]),
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
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = "123") { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Exception\Value\InvalidValue::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = "123") { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Exception\Value\InvalidValue::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int = "123") { fieldAbc { fieldXyz { name } } }',
                    'variables' => ['var1' => '123'],
                ]),
                \Graphpinator\Exception\Request\VariablesNotObject::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Int!) { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Exception\Value\ValueCannotBeNull::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Abc) { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Exception\Normalizer\VariableTypeInputable::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($var1: Abc!) { fieldAbc { fieldXyz { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Exception\Normalizer\VariableTypeInputable::class,
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAbc { fieldXyz(arg1: $varNonExistent) { name } } }',
                    'variables' => (object) [],
                ]),
                \Graphpinator\Exception\Resolver\MissingVariable::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Graphpinator\Json $request
     */
    public function testInvalid(\Graphpinator\Json $request, string $exception) : void
    {
        $this->expectException($exception);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));
    }
}
