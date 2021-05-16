<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InputTypeCycleTest extends \PHPUnit\Framework\TestCase
{
    private static function createNullableType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
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

    private static function createNullableInNullableListType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
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

    private static function createNonNullInNullableListType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
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

    private static function createNonNullInNonNullListType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
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

    private static function createNullableInNonNullListType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
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

    private static function createInvalidNonNullType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
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

    private static function createNullableAType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NullableSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableBType'),
                    ),
                ]);
            }
        };
    }

    private static function createNullableBType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NullableSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableAType'),
                    ),
                ]);
            }
        };
    }

    private static function createNullableInNullableListAType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NullableSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNullableListBType')->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNullableInNullableListBType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NullableSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNullableListAType')->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullInNullableListAType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNullableListCType')->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNullableInNullableListCType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNullableListAType')->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullInNonNullListAType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNullableListDType')->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNullableInNullableListDType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNonNullListAType')->notNullList(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullInNonNullListBType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNullableListBType')->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullInNullableListBType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNonNullListBType')->notNullList(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullInNonNullListCType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNonNullListDType')->notNullList(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullInNonNullListDType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNonNullListCType')->notNullList(),
                    ),
                ]);
            }
        };
    }

    private static function createNullableCType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullAType'),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullAType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableCType')->notNull(),
                    ),
                ]);
            }
        };
    }

    private static function createNullableInNullableListEType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullBType')->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullBType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nullableInNullableListEType')->notNull(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullInNullableListCType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullCType')->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullCType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNullableListCType')->notNull(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullInNonNullListEType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullDType')->notNullList(),
                    ),
                ]);
            }
        };
    }

    private static function createNonNullDType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('nonNullInNonNullListEType')->notNull(),
                    ),
                ]);
            }
        };
    }

    private static function createSimpleType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'SimpleType';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument(
                        'value',
                        \Graphpinator\Container\Container::String(),
                    ),
                ]);
            }
        };
    }

    private static function createValidationType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'ValidationType';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'arg',
                        InputTypeCycleTest::getType('simpleType')->notNull(),
                    )->setDefaultValue((object) ['value' => 'testValue']),
                ]);
            }
        };
    }

    private static function createInvalidNonNullAType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('invalidNonNullBType')->notNull(),
                    ),
                ]);
            }
        };
    }

    private static function createInvalidNonNullBType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType('invalidNonNullAType')->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function getType(string $typeName) : \Graphpinator\Type\InputType
    {
        return match ($typeName)
        {
            'nullableType' => self::createNullableType(),
            'nullableInNullableListType' => self::createNullableInNullableListType(),
            'nonNullInNullableListType' => self::createNonNullInNullableListType(),
            'nonNullInNonNullListType' => self::createNonNullInNonNullListType(),
            'nullableInNonNullListType' => self::createNullableInNonNullListType(),
            'nullableAType' => self::createNullableAType(),
            'nullableBType' => self::createNullableBType(),
            'nullableInNullableListAType' => self::createNullableInNullableListAType(),
            'nullableInNullableListBType' => self::createNullableInNullableListBType(),
            'nonNullInNullableListAType' => self::createNonNullInNullableListAType(),
            'nullableInNullableListCType' => self::createNullableInNullableListCType(),
            'nonNullInNonNullListAType' => self::createNonNullInNonNullListAType(),
            'nullableInNullableListDType' => self::createNullableInNullableListDType(),
            'nonNullInNullableListBType' => self::createNonNullInNullableListBType(),
            'nonNullInNonNullListBType' => self::createNonNullInNonNullListBType(),
            'nonNullInNonNullListCType' => self::createNonNullInNonNullListCType(),
            'nonNullInNonNullListDType' => self::createNonNullInNonNullListDType(),
            'nonNullAType' => self::createNonNullAType(),
            'nullableCType' => self::createNullableCType(),
            'nonNullBType' => self::createNonNullBType(),
            'nullableInNullableListEType' => self::createNullableInNullableListEType(),
            'nonNullCType' => self::createNonNullCType(),
            'nonNullInNullableListCType' => self::createNonNullInNullableListCType(),
            'nonNullDType' => self::createNonNullDType(),
            'nonNullInNonNullListEType' => self::createNonNullInNonNullListEType(),
            'validationType' => self::createValidationType(),
            'simpleType' => self::createSimpleType(),
            'invalidNonNullType' => self::createInvalidNonNullType(),
            'invalidNonNullAType' => self::createInvalidNonNullAType(),
            'invalidNonNullBType' => self::createInvalidNonNullBType(),
        };
    }

    public function testNullable() : void
    {
        self::getType('nullableType')->getArguments();

        self::assertTrue(true);
    }

    public function testNullableInNullableList() : void
    {
        self::getType('nullableInNullableListType')->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNullableList() : void
    {
        self::getType('nonNullInNullableListType')->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNonNullList() : void
    {
        self::getType('nonNullInNonNullListType')->getArguments();

        self::assertTrue(true);
    }

    public function testNullableInNonNullList() : void
    {
        self::getType('nullableInNonNullListType')->getArguments();

        self::assertTrue(true);
    }

    public function testNullableOnNullable() : void
    {
        self::getType('nullableAType')->getArguments();
        self::getType('nullableBType')->getArguments();

        self::assertTrue(true);
    }

    public function testNullableInNullableListOnNullableInNullableList() : void
    {
        self::getType('nullableInNullableListAType')->getArguments();
        self::getType('nullableInNullableListBType')->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNullableListOnNullableInNullableList() : void
    {
        self::getType('nonNullInNullableListAType')->getArguments();
        self::getType('nullableInNullableListCType')->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNonNullListOnNullableInNullableList() : void
    {
        self::getType('nonNullInNonNullListAType')->getArguments();
        self::getType('nullableInNullableListDType')->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNonNullListOnNonNullInNullableList() : void
    {
        self::getType('nonNullInNullableListBType')->getArguments();
        self::getType('nonNullInNonNullListBType')->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNonNullListOnNonNullInNonNullList() : void
    {
        self::getType('nonNullInNonNullListCType')->getArguments();
        self::getType('nonNullInNonNullListDType')->getArguments();

        self::assertTrue(true);
    }

    public function testNullableOnNonNull() : void
    {
        self::getType('nonNullAType')->getArguments();
        self::getType('nullableCType')->getArguments();

        self::assertTrue(true);
    }

    public function testNullableInNullableListOnNonNull() : void
    {
        self::getType('nonNullBType')->getArguments();
        self::getType('nullableInNullableListEType')->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNullableListOnNonNull() : void
    {
        self::getType('nonNullInNullableListCType')->getArguments();
        self::getType('nonNullCType')->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNonNullListOnNonNull() : void
    {
        self::getType('nonNullDType')->getArguments();
        self::getType('nonNullInNonNullListEType')->getArguments();

        self::assertTrue(true);
    }

    public function testValidation() : void
    {
        self::getType('validationType')->getArguments();

        self::assertTrue(true);
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

        self::getType('invalidNonNullAType')->getArguments();
        self::getType('invalidNonNullBType')->getArguments();
    }
}
