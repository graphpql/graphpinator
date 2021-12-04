<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class InputTypeCycleTest extends \PHPUnit\Framework\TestCase
{
    public static function createNullableType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableType(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNullableListType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNullableListType()->list()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNonNullListType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNonNullListType()->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createInvalidNonNullType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createInvalidNonNullType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableAType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NullableSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableBType(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableBType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NullableSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableAType(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListAType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NullableSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListBType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListBType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NullableSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListAType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNullableListAType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListCType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListCType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNullableListAType()->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListAType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListDType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListDType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListAType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListBType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNullableListBType()->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNullableListBType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListBType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListCType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListDType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListDType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListCType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableCType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullAType(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullAType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableCType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListEType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullBType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullBType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListEType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNullableListCType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullCType()->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullCType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNullableListCType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListEType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullDType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullDType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListEType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createSimpleType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'SimpleType';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    new \Graphpinator\Typesystem\Argument\Argument(
                        'value',
                        \Graphpinator\Typesystem\Container::String(),
                    ),
                ]);
            }
        };
    }

    public static function createValidationType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'ValidationType';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'arg',
                        InputTypeCycleTest::createSimpleType()->notNull(),
                    )->setDefaultValue((object) ['value' => 'testValue']),
                ]);
            }
        };
    }

    public static function createInvalidNonNullAType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createInvalidNonNullBType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createInvalidNonNullBType() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::createInvalidNonNullAType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public function testNullable() : void
    {
        self::createNullableType()->getArguments();

        self::assertTrue(true);
    }

    public function testNullableInNullableList() : void
    {
        self::createNullableInNullableListType()->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNullableList() : void
    {
        self::createNonNullInNullableListType()->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNonNullList() : void
    {
        self::createNonNullInNonNullListType()->getArguments();

        self::assertTrue(true);
    }

    public function testNullableInNonNullList() : void
    {
        self::createNullableInNonNullListType()->getArguments();

        self::assertTrue(true);
    }

    public function testNullableOnNullable() : void
    {
        self::createNullableAType()->getArguments();
        self::createNullableBType()->getArguments();

        self::assertTrue(true);
    }

    public function testNullableInNullableListOnNullableInNullableList() : void
    {
        self::createNullableInNullableListAType()->getArguments();
        self::createNullableInNullableListBType()->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNullableListOnNullableInNullableList() : void
    {
        self::createNonNullInNullableListAType()->getArguments();
        self::createNullableInNullableListCType()->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNonNullListOnNullableInNullableList() : void
    {
        self::createNonNullInNonNullListAType()->getArguments();
        self::createNullableInNullableListDType()->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNonNullListOnNonNullInNullableList() : void
    {
        self::createNonNullInNullableListBType()->getArguments();
        self::createNonNullInNonNullListBType()->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNonNullListOnNonNullInNonNullList() : void
    {
        self::createNonNullInNonNullListCType()->getArguments();
        self::createNonNullInNonNullListDType()->getArguments();

        self::assertTrue(true);
    }

    public function testNullableOnNonNull() : void
    {
        self::createNonNullAType()->getArguments();
        self::createNullableCType()->getArguments();

        self::assertTrue(true);
    }

    public function testNullableInNullableListOnNonNull() : void
    {
        self::createNonNullBType()->getArguments();
        self::createNullableInNullableListEType()->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNullableListOnNonNull() : void
    {
        self::createNonNullInNullableListCType()->getArguments();
        self::createNonNullCType()->getArguments();

        self::assertTrue(true);
    }

    public function testNonNullInNonNullListOnNonNull() : void
    {
        self::createNonNullDType()->getArguments();
        self::createNonNullInNonNullListEType()->getArguments();

        self::assertTrue(true);
    }

    public function testValidation() : void
    {
        self::createValidationType()->getArguments();

        self::assertTrue(true);
    }

    public function testInvalidNonNull() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\InputCycle::class);
        $this->expectDeprecationMessage('Input cycle detected (NotNullSelfCycle).');

        self::createInvalidNonNullType()->getArguments();
    }

    public function testInvalidNonNullOnNonNull() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\InputCycle::class);
        $this->expectDeprecationMessage('Input cycle detected (NotNullSelfCycleA -> NotNullSelfCycleB).');

        self::createInvalidNonNullAType()->getArguments();
        self::createInvalidNonNullBType()->getArguments();
    }
}
