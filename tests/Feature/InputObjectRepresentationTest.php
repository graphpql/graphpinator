<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InputObjectRepresentationTest extends \PHPUnit\Framework\TestCase
{
    public static function getSimpleInput() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'SimpleInput';
            protected const DATA_CLASS = \Graphpinator\Tests\Feature\InputObject::class;

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'number',
                        \Graphpinator\Typesystem\Container::Int(),
                    ),
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'simpleInput2',
                        \Graphpinator\Tests\Feature\InputObjectRepresentationTest::getSimpleInput2(),
                    ),
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'simpleInput3',
                        \Graphpinator\Tests\Feature\InputObjectRepresentationTest::getSimpleInput3(),
                    ),
                ]);
            }
        };
    }

    public static function getSimpleInput2() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'SimpleInput2';
            protected const DATA_CLASS = \Graphpinator\Tests\Feature\InputObject2::class;

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'number',
                        \Graphpinator\Typesystem\Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function getSimpleInput3() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'SimpleInput3';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'number',
                        \Graphpinator\Typesystem\Container::Int(),
                    ),
                ]);
            }
        };
    }

    public function testInputObject() : void
    {
        $request = \Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query queryName { field1(arg: { number: 123, 
                simpleInput2: {number: 123}, simpleInput3: {number: 123} }) }',
        ]);
        $expected = \Infinityloop\Utils\Json::fromNative((object) [
            'data' => [
                'field1' => 369,
            ],
        ]);

        $result = self::getGraphpinator()->run(new \Graphpinator\Request\JsonRequestFactory($request));
        self::assertSame($expected->toString(), $result->toString());
    }

    protected static function getGraphpinator() : \Graphpinator\Graphpinator
    {
        $query = new class () extends \Graphpinator\Typesystem\Type
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
                        'field1',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function($parent, \Graphpinator\Tests\Feature\InputObject $arg) : int {
                            \assert($arg->simpleInput2 instanceof \Graphpinator\Tests\Feature\InputObject2);
                            \assert($arg->simpleInput3 instanceof \stdClass);

                            return $arg->number + $arg->simpleInput2->number + $arg->simpleInput3->number;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        new \Graphpinator\Typesystem\Argument\Argument(
                            'arg',
                            \Graphpinator\Tests\Feature\InputObjectRepresentationTest::getSimpleInput(),
                        ),
                    ])),
                ]);
            }
        };

        return new \Graphpinator\Graphpinator(
            new \Graphpinator\Typesystem\Schema(
                new \Graphpinator\SimpleContainer(['Query' => $query], []),
                $query,
            ),
        );
    }
}
