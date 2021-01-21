<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InputObjectRepresentation extends \PHPUnit\Framework\TestCase
{
    public static function getSimpleInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'SimpleInput';
            protected const DATA_CLASS = \Graphpinator\Tests\Feature\InputObject::class;

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'name',
                        \Graphpinator\Container\Container::String()->notNull(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'number',
                        \Graphpinator\Container\Container::Int()->notNullList(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'bool',
                        \Graphpinator\Container\Container::Boolean(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'simpleInput2',
                        \Graphpinator\Tests\Feature\InputObjectRepresentation::getSimpleInput2(),
                    ),
                ]);
            }
        };
    }

    public static function getSimpleInput2() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'SimpleInput';
            protected const DATA_CLASS = \Graphpinator\Tests\Feature\InputObject2::class;

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'name',
                        \Graphpinator\Container\Container::String()->notNull(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'number',
                        \Graphpinator\Container\Container::Int()->notNullList(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'bool',
                        \Graphpinator\Container\Container::Boolean(),
                    ),
                ]);
            }
        };
    }

    public function testInputObject() : void
    {
        $request = \Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query queryName { field1(arg: {name: "foo", number: [123], bool: false, 
                simpleInput2: {name: "foo", number: [123], bool: false} }) }',
        ]);
        $expected = \Infinityloop\Utils\Json::fromNative((object) [
            'data' => [
                'field1' => 246,
            ],
        ]);

        $result = self::getGraphpinator()->run(new \Graphpinator\Request\JsonRequestFactory($request));
        self::assertSame($expected->toString(), $result->toString());
    }

    protected static function getGraphpinator() : \Graphpinator\Graphpinator
    {
        $query = new class () extends \Graphpinator\Type\Type
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

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'field1',
                        \Graphpinator\Container\Container::Int(),
                        static function($parent, \Graphpinator\Tests\Feature\InputObject $arg) : int {
                            \assert($arg->simpleInput2 instanceof \Graphpinator\Tests\Feature\InputObject2);

                            return $arg->number[0] + $arg->simpleInput2->number[0];
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'arg',
                            \Graphpinator\Tests\Feature\InputObjectRepresentation::getSimpleInput(),
                        ),
                    ])),
                ]);
            }
        };

        return new \Graphpinator\Graphpinator(
            new \Graphpinator\Type\Schema(
                new \Graphpinator\Container\SimpleContainer(['Query' => $query], []),
                $query,
            ),
        );
    }
}
