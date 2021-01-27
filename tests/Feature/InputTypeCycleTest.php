<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

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
        return match ($typeName)
        {
            'nullableType' => InputTypeCycleTest::createNullableType(),
            'nullableInNullableListType' => InputTypeCycleTest::createNullableInNullableListType(),
            'nonNullInNullableListType' => InputTypeCycleTest::createNonNullInNullableListType(),
            'nonNullInNonNullListType' => InputTypeCycleTest::createNonNullInNonNullListType(),
            'nullableInNonNullListType' => InputTypeCycleTest::createNullableInNonNullListType(),
            'nullableBooType' => InputTypeCycleTest::createNullableBooType(),
            'nullableBlahType' => InputTypeCycleTest::createNullableBlahType(),
            'nullableInNullableListBlahType' => InputTypeCycleTest::createNullableInNullableListBlahType(),
            'nullableInNullableListBooType' => InputTypeCycleTest::createNullableInNullableListBooType(),
            'nullableInNullableListBahType' => InputTypeCycleTest::createNullableInNullableListBahType(),
            'nonNullInNullableListBooType' => InputTypeCycleTest::createNonNullInNullableListBooType(),
            'nullableInNullableListBohType' => InputTypeCycleTest::createNullableInNullableListBohType(),
            'nonNullInNonNullListBooType' => InputTypeCycleTest::createNonNullInNonNullListBooType(),
            'nonNullInNullableListBlahType' => InputTypeCycleTest::createNonNullInNullableListBlahType(),
            'nonNullInNonNullListBahType' => InputTypeCycleTest::createNonNullInNonNullListBahType(),
            'nonNullInNonNullListBlahType' => InputTypeCycleTest::createNonNullInNonNullListBlahType(),
            'nonNullInNonNullListBohType' => InputTypeCycleTest::createNonNullInNonNullListBohType(),
            'invalidNonNullType' => InputTypeCycleTest::createInvalidNonNullType(),
            'invalidNonNullBlahType' => InputTypeCycleTest::createInvalidNonNullBlahType(),
            'invalidNonNullBooType' => InputTypeCycleTest::createInvalidNonNullBooType(),
            'nonNullBahType' => InputTypeCycleTest::createNonNullBahType(),
            'nullableBoohType' => InputTypeCycleTest::createNullableBoohType(),
            'nonNullBohType' => InputTypeCycleTest::createNonNullBohType(),
            'nullableInNullableListBoohType' => InputTypeCycleTest::createNullableInNullableListBoohType(),
            'nonNullBleType' => InputTypeCycleTest::createNonNullBleType(),
            'nonNullInNullableListBoohType' => InputTypeCycleTest::createNonNullInNullableListBoohType(),
            'nonNullBehType' => InputTypeCycleTest::createNonNullBehType(),
            'nonNullInNonNullListBoohType' => InputTypeCycleTest::createNonNullInNonNullListBoohType(),
        };
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNullable() : void
    {
        self::getType('nullableType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNullableInNullableList() : void
    {
        self::getType('nullableInNullableListType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNonNullInNullableList() : void
    {
        self::getType('nonNullInNullableListType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNonNullInNonNullList() : void
    {
        self::getType('nonNullInNonNullListType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNullableInNonNullList() : void
    {
        self::getType('nullableInNonNullListType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNullableOnNullable() : void
    {
        self::getType('nullableBlahType')->getArguments();
        self::getType('nullableBooType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNullableInNullableListOnNullableInNullableList() : void
    {
        self::getType('nullableInNullableListBooType')->getArguments();
        self::getType('nullableInNullableListBlahType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNonNullInNullableListOnNullableInNullableList() : void
    {
        self::getType('nullableInNullableListBahType')->getArguments();
        self::getType('nonNullInNullableListBooType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNonNullInNonNullListOnNullableInNullableList() : void
    {
        self::getType('nullableInNullableListBohType')->getArguments();
        self::getType('nonNullInNonNullListBooType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNonNullInNonNullListOnNonNullInNullableList() : void
    {
        self::getType('nonNullInNullableListBlahType')->getArguments();
        self::getType('nonNullInNonNullListBahType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNonNullInNonNullListOnNonNullInNonNullList() : void
    {
        self::getType('nonNullInNonNullListBohType')->getArguments();
        self::getType('nonNullInNonNullListBlahType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNullableOnNonNull() : void
    {
        self::getType('nonNullBahType')->getArguments();
        self::getType('nullableBooType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNullableInNullableListOnNonNull() : void
    {
        self::getType('nonNullBohType')->getArguments();
        self::getType('nullableInNullableListBooType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNonNullInNullableListOnNonNull() : void
    {
        self::getType('nonNullInNullableListBooType')->getArguments();
        self::getType('nonNullBleType')->getArguments();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testNonNullInNonNullListOnNonNull() : void
    {
        self::getType('nonNullBehType')->getArguments();
        self::getType('nonNullInNonNullListBooType')->getArguments();
    }

    public function testInvalidNonNull() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InputCycle::class);
        $this->expectDeprecationMessage(\Graphpinator\Exception\Type\InputCycle::MESSAGE);

        self::getType('invalidNonNullType')->getArguments();
    }

    public function testInvalidNonNullOnNonNull() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InputCycle::class);
        $this->expectDeprecationMessage(\Graphpinator\Exception\Type\InputCycle::MESSAGE);

        self::getType('invalidNonNullBlahType')->getArguments();
        self::getType('invalidNonNullBooType')->getArguments();
    }
}
