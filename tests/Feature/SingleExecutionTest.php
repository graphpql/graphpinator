<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class SingleExecutionTest extends \PHPUnit\Framework\TestCase
{
    public static int $execCount;

    public function testSimple() : void
    {
        self::$execCount = 0;

        $request = \Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query { 
                field
                field
             }',
        ]);
        $expected = \Infinityloop\Utils\Json::fromNative((object) [
            'data' => [
                'field' => 123,
            ],
        ]);

        self::assertSame(0, self::$execCount);
        $result = self::getGraphpinator()->run(new \Graphpinator\Request\JsonRequestFactory($request));
        self::assertSame($expected->toString(), $result->toString());
        self::assertSame(1, self::$execCount);
    }

    public function testFragment() : void
    {
        self::$execCount = 0;

        $request = \Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query { 
                field
                ... @skip(if: false) {
                  field
                }
             }',
        ]);
        $expected = \Infinityloop\Utils\Json::fromNative((object) [
            'data' => [
                'field' => 123,
            ],
        ]);

        self::assertSame(0, self::$execCount);
        $result = self::getGraphpinator()->run(new \Graphpinator\Request\JsonRequestFactory($request));
        self::assertSame($expected->toString(), $result->toString());
        self::assertSame(1, self::$execCount);
    }

    public function testInnerFields() : void
    {
        self::$execCount = 0;

        $request = \Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query { 
                compositeField {
                  firstField
                }
                ... @skip(if: false) {
                  compositeField {
                    secondField  
                  }
                }
             }',
        ]);
        $expected = \Infinityloop\Utils\Json::fromNative((object) [
            'data' => [
                'compositeField' => (object) [
                    'firstField' => 123,
                    'secondField' => 456,
                ],
            ],
        ]);

        self::assertSame(0, self::$execCount);
        $result = self::getGraphpinator()->run(new \Graphpinator\Request\JsonRequestFactory($request));
        self::assertSame($expected->toString(), $result->toString());
        self::assertSame(1, self::$execCount);
    }

    protected static function getGraphpinator() : \Graphpinator\Graphpinator
    {
        $query = self::getQuery();

        return new \Graphpinator\Graphpinator(
            new \Graphpinator\Typesystem\Schema(
                new \Graphpinator\SimpleContainer(['Query' => $query, 'CompositeType' => self::getCompositeType()], []),
                $query,
            ),
        );
    }

    protected static function getQuery() : \Graphpinator\Typesystem\Type
    {
        return new class () extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'Query';

            public function __construct()
            {
                parent::__construct();
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'field',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function($parent) : int {
                            ++SingleExecutionTest::$execCount;

                            return 123;
                        },
                    ),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'compositeField',
                        SingleExecutionTest::getCompositeType()->notNull(),
                        static function($parent) : int {
                            ++SingleExecutionTest::$execCount;

                            return 123;
                        },
                    ),
                ]);
            }
        };
    }

    public static function getCompositeType() : \Graphpinator\Typesystem\Type
    {
        return new class () extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'CompositeType';

            public function __construct()
            {
                parent::__construct();
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'firstField',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function($parent) : int {
                            return 123;
                        },
                    ),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'secondField',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function($parent) : int {
                            return 456;
                        },
                    ),
                ]);
            }
        };
    }
}
