<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class ArgumentTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                'query queryName { field0 { field1(arg1: 456) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
                \Infinityloop\Utils\Json::fromArray(['data' => ['field0' => ['field1' => ['name' => 'Test 456']]]]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string $request
     * @param \Infinityloop\Utils\Json $variables
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testSimple(string $request, \Infinityloop\Utils\Json $variables, \Infinityloop\Utils\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->runQuery($request, $variables);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame($expected['data'], \json_decode(\json_encode($result->getData()), true));
        self::assertNull($result->getErrors());
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                'query queryName { field0 { field1(argNonExistent: 123) { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
            ],
            [
                'query queryName { field0 { field1(arg1: "123") { name } } }',
                \Infinityloop\Utils\Json::fromArray([]),
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * @param string $request
     * @param \Infinityloop\Utils\Json $variables
     */
    public function testInvalid(string $request, \Infinityloop\Utils\Json $variables) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $graphpinator->runQuery($request, $variables);
    }
}
