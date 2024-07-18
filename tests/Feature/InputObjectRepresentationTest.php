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

final class InputObjectRepresentationTest extends TestCase
{
    public static function getSimpleInput() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'SimpleInput';
            protected const DATA_CLASS = InputObject::class;

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument(
                        'number',
                        Container::Int(),
                    ),
                    new Argument(
                        'simpleInput2',
                        InputObjectRepresentationTest::getSimpleInput2(),
                    ),
                    new Argument(
                        'simpleInput3',
                        InputObjectRepresentationTest::getSimpleInput3(),
                    ),
                ]);
            }
        };
    }

    public static function getSimpleInput2() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'SimpleInput2';
            protected const DATA_CLASS = InputObject2::class;

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument(
                        'number',
                        Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function getSimpleInput3() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'SimpleInput3';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument(
                        'number',
                        Container::Int(),
                    ),
                ]);
            }
        };
    }

    public function testInputObject() : void
    {
        $request = Json::fromNative((object) [
            'query' => 'query queryName { field1(arg: { number: 123, 
                simpleInput2: {number: 123}, simpleInput3: {number: 123} }) }',
        ]);
        $expected = Json::fromNative((object) [
            'data' => [
                'field1' => 369,
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
                        Container::Int(),
                        static function($parent, InputObject $arg) : int {
                            \assert($arg->simpleInput2 instanceof InputObject2);
                            \assert($arg->simpleInput3 instanceof \stdClass);

                            return $arg->number + $arg->simpleInput2->number + $arg->simpleInput3->number;
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'arg',
                            InputObjectRepresentationTest::getSimpleInput(),
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
