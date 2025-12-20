<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Graphpinator;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class InputTypeCoercionTest extends TestCase
{
    public static function getSimpleInput() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'SimpleInput';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument(
                        'string',
                        Container::String(),
                    ),
                    new Argument(
                        'stringNotNull',
                        Container::String()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function inputObjectDataProvider() : array
    {
        return [
            [
                'query queryName { field1(arg: {stringNotNull: "value"}) }',
                'missing value',
            ],
            [
                'query queryName { field1(arg: {string: "optional", stringNotNull: "value"}) }',
                'optional value',
            ],
            [
                'query queryName { field1(arg: {string: null, stringNotNull: "value"}) }',
                ' value',
            ],
        ];
    }

    /**
     * @dataProvider inputObjectDataProvider
     */
    public function testInputObject(string $query, string $expected) : void
    {
        $request = Json::fromNative((object) [
            'query' => $query,
        ]);
        $expected = Json::fromNative((object) [
            'data' => [
                'field1' => $expected,
            ],
        ]);

        $result = self::getGraphpinator()->run(new JsonRequestFactory($request));
        self::assertSame($expected->toString(), $result->toString());
    }

    protected static function getGraphpinator() : Graphpinator
    {
        $query = new class () extends Type
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
                        'field1',
                        Container::String()->notNull(),
                        static function($parent, \stdClass $arg) : string {
                            $first = \property_exists($arg, 'string')
                                ? $arg->string
                                : 'missing';

                            return $first . ' ' . $arg->stringNotNull;
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'arg',
                            InputTypeCoercionTest::getSimpleInput(),
                        ),
                    ])),
                ]);
            }
        };

        return new Graphpinator(
            new Schema(
                new SimpleContainer(['Query' => $query], []),
                $query,
            ),
        );
    }
}
