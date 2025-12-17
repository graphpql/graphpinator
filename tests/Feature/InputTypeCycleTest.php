<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Exception\InputCycleDetected;
use Graphpinator\Typesystem\InputType;
use PHPUnit\Framework\TestCase;

final class InputTypeCycleTest extends TestCase
{
    public static function createNullableType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableType(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNullableListType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNullableListType()->list()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNonNullListType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNonNullListType()->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createInvalidNonNullType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createInvalidNonNullType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableAType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NullableSelfCycleA';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableBType(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableBType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NullableSelfCycleB';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableAType(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListAType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NullableSelfCycleA';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListBType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListBType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NullableSelfCycleB';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListAType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNullableListAType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListCType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListCType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNullableListAType()->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListAType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListDType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListDType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListAType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListBType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNullableListBType()->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNullableListBType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListBType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListCType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListDType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListDType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListCType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableCType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullAType(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullAType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableCType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createNullableInNullableListEType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NullableSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullBType()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullBType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycle';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNullableInNullableListEType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNullableListCType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullCType()->notNull()->list(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullCType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNullableListCType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullInNonNullListEType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullDType()->notNullList(),
                    ),
                ]);
            }
        };
    }

    public static function createNonNullDType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createNonNullInNonNullListEType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createSimpleType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'SimpleType';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument(
                        'value',
                        Container::String(),
                    ),
                ]);
            }
        };
    }

    public static function createValidationType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'ValidationType';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'arg',
                        InputTypeCycleTest::createSimpleType()->notNull(),
                    )->setDefaultValue((object) ['value' => 'testValue']),
                ]);
            }
        };
    }

    public static function createInvalidNonNullAType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycleA';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
                        'cycle',
                        InputTypeCycleTest::createInvalidNonNullBType()->notNull(),
                    ),
                ]);
            }
        };
    }

    public static function createInvalidNonNullBType() : InputType
    {
        return new class extends InputType
        {
            protected const NAME = 'NotNullSelfCycleB';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create(
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
        $this->expectException(InputCycleDetected::class);
        $this->expectExceptionMessage('Input cycle detected (NotNullSelfCycle).');

        self::createInvalidNonNullType()->getArguments();
    }

    public function testInvalidNonNullOnNonNull() : void
    {
        $this->expectException(InputCycleDetected::class);
        $this->expectExceptionMessage('Input cycle detected (NotNullSelfCycleA -> NotNullSelfCycleB).');

        self::createInvalidNonNullAType()->getArguments();
        self::createInvalidNonNullBType()->getArguments();
    }
}
