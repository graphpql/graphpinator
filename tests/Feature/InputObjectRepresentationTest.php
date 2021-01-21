<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InputObjectRepresentationTest extends \PHPUnit\Framework\TestCase
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
                        'number',
                        \Graphpinator\Container\Container::Int(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'simpleInput2',
                        \Graphpinator\Tests\Feature\InputObjectRepresentationTest::getSimpleInput2(),
                    ),
                    new \Graphpinator\Argument\Argument(
                        'simpleInput3',
                        \Graphpinator\Tests\Feature\InputObjectRepresentationTest::getSimpleInput3(),
                    ),
                ]);
            }
        };
    }

    public static function getSimpleInput2() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'SimpleInput2';
            protected const DATA_CLASS = \Graphpinator\Tests\Feature\InputObject2::class;

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'number',
                        \Graphpinator\Container\Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function getSimpleInput3() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'SimpleInput3';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'number',
                        \Graphpinator\Container\Container::Int(),
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
                            \assert($arg->simpleInput3 instanceof \stdClass);

                            return $arg->number + $arg->simpleInput2->number + $arg->simpleInput3->number;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'arg',
                            \Graphpinator\Tests\Feature\InputObjectRepresentationTest::getSimpleInput(),
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
