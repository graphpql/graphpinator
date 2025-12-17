<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Value;

use Graphpinator\Common\Path;
use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\Variable\Variable;
use Graphpinator\Tests\Spec\TestSchema;
use Graphpinator\Typesystem\Spec\StringType;
use Graphpinator\Value\InputedValue;
use Graphpinator\Value\VariableValue;
use Graphpinator\Value\Visitor\ConvertRawValueVisitor;
use PHPUnit\Framework\TestCase;

final class IsSameTest extends TestCase
{
    public static function simpleDataProvider() : array
    {
        return [
            [
                new VariableValue(
                    new StringType(),
                    new Variable(
                        'var1',
                        new StringType(),
                        null,
                        new DirectiveSet(),
                    ),
                ),
                new VariableValue(
                    new StringType(),
                    new Variable(
                        'var1',
                        new StringType(),
                        null,
                        new DirectiveSet(),
                    ),
                ),
                true,
            ],
            [
                new VariableValue(
                    new StringType(),
                    new Variable(
                        'var1',
                        new StringType(),
                        null,
                        new DirectiveSet(),
                    ),
                ),
                new VariableValue(
                    new StringType(),
                    new Variable(
                        'var2',
                        new StringType(),
                        null,
                        new DirectiveSet(),
                    ),
                ),
                false,
            ],
            [
                TestSchema::getSimpleInput()->accept(
                    new ConvertRawValueVisitor(
                        (object) [
                            'name' => 'test',
                            'number' => [],
                        ],
                        new Path(),
                    ),
                ),
                TestSchema::getSimpleInput()->accept(
                    new ConvertRawValueVisitor(
                        (object) [
                            'name' => 'test',
                            'number' => [],
                        ],
                        new Path(),
                    ),
                ),
                true,
            ],
            [
                TestSchema::getSimpleInput()->accept(
                    new ConvertRawValueVisitor(
                        (object) [
                            'name' => 'test',
                            'number' => [],
                        ],
                        new Path(),
                    ),
                ),
                new VariableValue(
                    new StringType(),
                    new Variable(
                        'var2',
                        new StringType(),
                        null,
                        new DirectiveSet(),
                    ),
                ),
                false,
            ],
            [
                TestSchema::getSimpleInput()->accept(
                    new ConvertRawValueVisitor(
                        (object) [
                            'name' => 'test',
                            'number' => [],
                        ],
                        new Path(),
                    ),
                ),
                TestSchema::getSimpleInput()->accept(
                    new ConvertRawValueVisitor(
                        (object) [
                            'name' => 'test',
                            'number' => [],
                            'bool' => true,
                        ],
                        new Path(),
                    ),
                ),
                false,
            ],
            [
                TestSchema::getSimpleInput()->accept(
                    new ConvertRawValueVisitor(
                        (object) [
                            'name' => 'test',
                            'number' => [],
                        ],
                        new Path(),
                    ),
                ),
                TestSchema::getSimpleInput()->accept(
                    new ConvertRawValueVisitor(
                        (object) [
                            'name' => 'test',
                            'number' => [1],
                        ],
                        new Path(),
                    ),
                ),
                false,
            ],
            [
                TestSchema::getSimpleInput()->accept(
                    new ConvertRawValueVisitor(
                        (object) [
                            'name' => 'test',
                            'number' => [1],
                        ],
                        new Path(),
                    ),
                ),
                TestSchema::getSimpleInput()->accept(
                    new ConvertRawValueVisitor(
                        (object) [
                            'name' => 'test',
                            'number' => [1, 2],
                        ],
                        new Path(),
                    ),
                ),
                false,
            ],
            [
                TestSchema::getSimpleInput()->accept(
                    new ConvertRawValueVisitor(
                        (object) [
                            'name' => 'test',
                            'number' => [1, 2],
                        ],
                        new Path(),
                    ),
                ),
                TestSchema::getSimpleInput()->accept(
                    new ConvertRawValueVisitor(
                        (object) [
                            'name' => 'test',
                            'number' => [2, 1],
                        ],
                        new Path(),
                    ),
                ),
                false,
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param InputedValue $lhs
     * @param InputedValue $rhs
     * @param bool $result
     */
    public function testSimple(InputedValue $lhs, InputedValue $rhs, bool $result) : void
    {
        self::assertSame($result, $lhs->isSame($rhs));
    }
}
