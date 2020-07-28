<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class ErrorsTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName ($ var1: Int) { }',
                ]),
                \Infinityloop\Utils\Json::fromArray([
                    'errors' => [
                        [
                            'message' => \Graphpinator\Exception\Tokenizer\MissingVariableName::MESSAGE,
                            'locations' => [['line' => 0, 'column' => 18]],
                        ],
                    ],
                ]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 { ',
                ]),
                \Infinityloop\Utils\Json::fromArray(['errors' => [['message' => \Graphpinator\Exception\Parser\UnexpectedEnd::MESSAGE]]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { field0 @invalidDirective() { field1 { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['errors' => [['message' => 'Server responded with unknown error.']]]),
            ],
            [
                \Infinityloop\Utils\Json::fromArray([
                    'query' => 'query queryName { fieldThrow { field1 { name } } }',
                ]),
                \Infinityloop\Utils\Json::fromArray(['errors' => [['message' => 'Server responded with unknown error.']]]),
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
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema(), true);
        $result = $graphpinator->runQuery($request);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame($expected['errors'], \json_decode(\json_encode($result->getErrors(), \JSON_THROW_ON_ERROR, 512), true, 512, \JSON_THROW_ON_ERROR));
        self::assertNull($result->getData());
    }

    public function testErrorData() : void
    {
        $location = new \Graphpinator\Source\Location(6, 7);
        $path = new \Graphpinator\Exception\Path([ 'hero', 'heroFriends', 1, 'name' ]);
        $extensions = [
            'code' => 'CAN_NOT_FETCH_BY_ID',
            'timestamp' => 'Fri Feb 9 14:33:09 UTC 2018',
        ];
        $exception = new \Graphpinator\Exception\Parser\UnexpectedEnd($location, $path, $extensions);

        self::assertSame(
            //@phpcs:ignore SlevomatCodingStandard.Files.LineLength.LineTooLong
            '{"message":"Unexpected end of stream.","locations":[{"line":6,"column":7}],"path":["hero","heroFriends",1,"name"],"extensions":{"code":"CAN_NOT_FETCH_BY_ID","timestamp":"Fri Feb 9 14:33:09 UTC 2018"}}',
            \json_encode($exception, \JSON_THROW_ON_ERROR, 512),
        );
    }
}
