<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class ArgumentTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1(arg1: 456) { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 456']]]]),
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
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1(argNonExistent: 123) { name } } }',
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { field1(arg1: "123") { name } } }',
                ]),
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param \Infinityloop\Utils\Json $request
     */
    public function testInvalid(\Infinityloop\Utils\Json $request) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request);
    }
}
