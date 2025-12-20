<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Graphpinator;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class SingleExecutionTest extends TestCase
{
    public static int $execCount;

    public static function getCompositeType() : Type
    {
        return new class () extends Type
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

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'firstField',
                        Container::Int()->notNull(),
                        static function($parent) : int {
                            return 123;
                        },
                    ),
                    ResolvableField::create(
                        'secondField',
                        Container::Int()->notNull(),
                        static function($parent) : int {
                            return 456;
                        },
                    ),
                ]);
            }
        };
    }

    public function testSimple() : void
    {
        self::$execCount = 0;

        $request = Json::fromNative((object) [
            'query' => 'query { 
                field
                field
             }',
        ]);
        $expected = Json::fromNative((object) [
            'data' => [
                'field' => 123,
            ],
        ]);

        self::assertSame(0, self::$execCount);
        $result = self::getGraphpinator()->run(new JsonRequestFactory($request));
        self::assertSame($expected->toString(), $result->toString());
        self::assertSame(1, self::$execCount);
    }

    public function testFragment() : void
    {
        self::$execCount = 0;

        $request = Json::fromNative((object) [
            'query' => 'query { 
                field
                ... @skip(if: false) {
                  field
                }
             }',
        ]);
        $expected = Json::fromNative((object) [
            'data' => [
                'field' => 123,
            ],
        ]);

        self::assertSame(0, self::$execCount);
        $result = self::getGraphpinator()->run(new JsonRequestFactory($request));
        self::assertSame($expected->toString(), $result->toString());
        self::assertSame(1, self::$execCount);
    }

    public function testInnerFields() : void
    {
        self::$execCount = 0;

        $request = Json::fromNative((object) [
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
        $expected = Json::fromNative((object) [
            'data' => [
                'compositeField' => (object) [
                    'firstField' => 123,
                    'secondField' => 456,
                ],
            ],
        ]);

        self::assertSame(0, self::$execCount);
        $result = self::getGraphpinator()->run(new JsonRequestFactory($request));
        self::assertSame($expected->toString(), $result->toString());
        self::assertSame(1, self::$execCount);
    }

    protected static function getGraphpinator() : Graphpinator
    {
        $query = self::getQuery();

        return new Graphpinator(
            new Schema(
                new SimpleContainer(['Query' => $query, 'CompositeType' => self::getCompositeType()], []),
                $query,
            ),
        );
    }

    protected static function getQuery() : Type
    {
        return new class () extends Type
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

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'field',
                        Container::Int()->notNull(),
                        static function($parent) : int {
                            ++SingleExecutionTest::$execCount;

                            return 123;
                        },
                    ),
                    ResolvableField::create(
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
}
