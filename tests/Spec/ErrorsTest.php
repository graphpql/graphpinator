<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class ErrorsTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName ($ var1: Int) { }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'errors' => [
                        [
                            'message' => \Graphpinator\Exception\Tokenizer\MissingVariableName::MESSAGE,
                            'locations' => [['line' => 0, 'column' => 18]],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldValid { ',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => \Graphpinator\Exception\Parser\UnexpectedEnd::MESSAGE]]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldValid @invalidDirective() { field1 { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Server responded with unknown error.']]]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldThrow { field1 { name } } }',
                ]),
                \Graphpinator\Json::fromObject((object) ['errors' => [['message' => 'Server responded with unknown error.']]]),
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
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema(), true);
        $result = $graphpinator->runQuery($request);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame(
            $expected['errors'],
            \json_decode(\json_encode($result->getErrors(), \JSON_THROW_ON_ERROR, 512), true, 512, \JSON_THROW_ON_ERROR),
        );
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
