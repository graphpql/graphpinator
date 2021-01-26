<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature\Cycle;

final class InputTypeCycleTest extends \PHPUnit\Framework\TestCase
{
    /* Self-ref Nullable ++*/
    private static function createNullableType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableType'),
                    ),
                ]);
            }
        };
    }

    /* Self-ref NullableInNullableList ++*/
    private static function createNullableInNullableListType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNullableListType')->list(),
                    ),
                ]);
            }
        };
    }

    /* Self-ref NonNullInNullableList */
    private static function createNonNullInNullableListType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNullableListType')->list()->notNull(),
                    ),
                ]);
            }
        };
    }

    /* Self-ref NonNullInNonNullList */
    private static function createNonNullInNonNullListType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNonNullListType')->notNullList(),
                    ),
                ]);
            }
        };
    }

    /* Self-ref NullableInNonNullList */
    private static function createNullableInNonNullListType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNonNullListType')->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    /* Self-ref Invalid - NonNull */
    private static function createInvalidNonNullType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('invalidNonNullType')->notNull(),
                    ),
                ]);
            }
        };
    }

    /* Objects-ref Nullable on Nullable */
    private static function createNullableBooType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableBlahType'),
                    ),
                ]);
            }
        };
    }

    private static function createNullableBlahType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableBooType'),
                    ),
                ]);
            }
        };
    }

    /* Objects-ref NullableInNullableList on NullableInNullableList */
    private static function createNullableInNullableListBooType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNullableListBlahType')->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNullableInNullableListBlahType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNullableListBooType')->list(),
                    ),
                ]);
            }
        };
    }

    /* Objects-ref NonNullInNullableList on NullableInNullableList */
    private static function createNonNullInNullableListBooType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNullableListBahType')->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNullableInNullableListBahType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNullableListBooType')->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    /* Objects-ref NonNullInNonNullList on NullableInNullableList */
    private static function createNonNullInNonNullListBooType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNullableListBohType')->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNullableInNullableListBohType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNonNullListBooType')->notNullList(),
                    ),
                ]);
            }
        };
    }

    /* Objects-ref NonNullInNonNullList on NonNullInNullableList */
    private static function createNonNullInNonNullListBahType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNullableListBlahType')->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullInNullableListBlahType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNonNullListBahType')->notNullList(),
                    ),
                ]);
            }
        };
    }

    /* Objects-ref NonNullInNonNullList on NonNullInNonNullList */
    private static function createNonNullInNonNullListBohType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNonNullListBlahType')->notNullList(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullInNonNullListBlahType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNonNullListBohType')->notNullList(),
                    ),
                ]);
            }
        };
    }

    /* Objects-ref Nullable on NonNull */
    private static function createNullableBoohType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullBahType'),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullBahType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableBoohType')->notNull(),
                    ),
                ]);
            }
        };
    }

    /* Objects-ref NullableInNullableList on NonNull */
    private static function createNullableInNullableListBoohType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullBohType')->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullBohType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNullableListBoohType')->notNull(),
                    ),
                ]);
            }
        };
    }

    /* Objects-ref NonNullInNullableList on NonNull */
    private static function createNonNullInNullableListBoohType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullBleType')->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullBleType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNullableListBoohType')->notNull(),
                    ),
                ]);
            }
        };
    }

    /* Objects-ref NonNullInNonNullList on NonNull */
    private static function createNonNullInNonNullListBoohType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullBehType')->notNullList(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullBehType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNonNullListBoohType')->notNull(),
                    ),
                ]);
            }
        };
    }

    /* Objects-ref Invalid - NonNull on NonNull */
    private static function createInvalidNonNullBooType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('invalidNonNullBlahType')->notNull(),
                    ),
                ]);
            }
        };
    }

    private static function createInvalidNonNullBlahType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('invalidNonNullBooType')->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function getType(string $typeName) : \Graphpinator\Type\InputType
    {
        switch ($typeName)
        {
            case 'nullableType':
                return InputTypeCycleTest::createNullableType();
            case 'nullableInNullableListType':
                return InputTypeCycleTest::createNullableInNullableListType();
            case 'nonNullInNullableListType':
                return InputTypeCycleTest::createNonNullInNullableListType();
            case 'nonNullInNonNullListType':
                return InputTypeCycleTest::createNonNullInNonNullListType();
            case 'nullableInNonNullListType':
                return InputTypeCycleTest::createNullableInNonNullListType();
            case 'nullableBooType':
                return InputTypeCycleTest::createNullableBooType();
            case 'nullableBlahType':
                return InputTypeCycleTest::createNullableBlahType();
            case 'nullableInNullableListBlahType':
                return InputTypeCycleTest::createNullableInNullableListBlahType();
            case 'nullableInNullableListBooType':
                return InputTypeCycleTest::createNullableInNullableListBooType();
            case 'nullableInNullableListBahType':
                return InputTypeCycleTest::createNullableInNullableListBahType();
            case 'nonNullInNullableListBooType':
                return InputTypeCycleTest::createNonNullInNullableListBooType();
            case 'nullableInNullableListBohType':
                return InputTypeCycleTest::createNullableInNullableListBohType();
            case 'nonNullInNonNullListBooType':
                return InputTypeCycleTest::createNonNullInNonNullListBooType();
            case 'nonNullInNullableListBlahType':
                return InputTypeCycleTest::createNonNullInNullableListBlahType();
            case 'nonNullInNonNullListBahType':
                return InputTypeCycleTest::createNonNullInNonNullListBahType();
            case 'nonNullInNonNullListBlahType':
                return InputTypeCycleTest::createNonNullInNonNullListBlahType();
            case 'nonNullInNonNullListBohType':
                return InputTypeCycleTest::createNonNullInNonNullListBohType();
            case 'invalidNonNullType':
                return InputTypeCycleTest::createInvalidNonNullType();
            case 'invalidNonNullBlahType':
                return InputTypeCycleTest::createInvalidNonNullBlahType();
            case 'invalidNonNullBooType':
                return InputTypeCycleTest::createInvalidNonNullBooType();
            case 'nonNullBahType':
                return InputTypeCycleTest::createNonNullBahType();
            case 'nullableBoohType':
                return InputTypeCycleTest::createNullableBoohType();
            case 'nonNullBohType':
                return InputTypeCycleTest::createNonNullBohType();
            case 'nullableInNullableListBoohType':
                return InputTypeCycleTest::createNullableInNullableListBoohType();
            case 'nonNullBleType':
                return InputTypeCycleTest::createNonNullBleType();
            case 'nonNullInNullableListBoohType':
                return InputTypeCycleTest::createNonNullInNullableListBoohType();
            case 'nonNullBehType':
                return InputTypeCycleTest::createNonNullBehType();
            case 'nonNullInNonNullListBoohType':
                return InputTypeCycleTest::createNonNullInNonNullListBoohType();
        }
    }

    public function testNullable() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nullableType')->getArguments(),
        );
    }

    public function testNullableInNullableList() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nullableInNullableListType')->getArguments(),
        );
    }

    public function testNonNullInNullableList() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullInNullableListType')->getArguments(),
        );
    }

    public function testNonNullInNonNullList() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullInNonNullListType')->getArguments(),
        );
    }

    public function testNullableInNonNullList() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nullableInNonNullListType')->getArguments(),
        );
    }

    public function testInvalidNonNull() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InputCycle::class);
        $this->expectDeprecationMessage(\Graphpinator\Exception\Type\InputCycle::MESSAGE);

        self::getType('invalidNonNullType')->getArguments();
    }

    public function testNullableOnNullable() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nullableBooType')->getArguments(),
        );
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nullableBlahType')->getArguments(),
        );
    }

    public function testNullableInNullableListOnNullableInNullableList() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nullableInNullableListBlahType')->getArguments(),
        );
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nullableInNullableListBooType')->getArguments(),
        );
    }

    public function testNonNullInNullableListOnNullableInNullableList() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nullableInNullableListBahType')->getArguments(),
        );
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullInNullableListBooType')->getArguments(),
        );
    }

    public function testNonNullInNonNullListOnNullableInNullableList() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nullableInNullableListBohType')->getArguments(),
        );
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullInNonNullListBooType')->getArguments(),
        );
    }

    public function testNonNullInNonNullListOnNonNullInNullableList() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullInNullableListBlahType')->getArguments(),
        );
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullInNonNullListBahType')->getArguments(),
        );
    }

    public function testNonNullInNonNullListOnNonNullInNonNullList() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullInNonNullListBlahType')->getArguments(),
        );
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullInNonNullListBohType')->getArguments(),
        );
    }


    public function testNullableOnNonNull() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullBahType')->getArguments(),
        );
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nullableBooType')->getArguments(),
        );
    }

    public function testNullableInNullableListOnNonNull() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullBohType')->getArguments(),
        );
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nullableInNullableListBooType')->getArguments(),
        );
    }

    public function testNonNullInNullableListOnNonNull() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullBleType')->getArguments(),
        );
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullInNullableListBooType')->getArguments(),
        );
    }

    public function testNonNullInNonNullListOnNonNull() : void
    {
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullBehType')->getArguments(),
        );
        self::assertInstanceOf(
            \Graphpinator\Argument\ArgumentSet::class,
            self::getType('nonNullInNonNullListBooType')->getArguments(),
        );
    }

    public function testInvalidNonNullOnNonNull() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InputCycle::class);
        $this->expectDeprecationMessage(\Graphpinator\Exception\Type\InputCycle::MESSAGE);

        self::getType('invalidNonNullBlahType')->getArguments();
        self::getType('invalidNonNullBooType')->getArguments();
    }
}
