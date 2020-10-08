<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type;

final class InterfaceTypeTest extends \PHPUnit\Framework\TestCase
{
    public static function createInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createParentInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                    ),
                    new \Graphpinator\Field\Field(
                        'fieldNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                    ),
                    new \Graphpinator\Field\Field(
                        'argument',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\Field(
                        'argumentNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int()->notNull(),
                            ),
                        ]),
                    ),
                ]);
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(InterfaceTypeTest::getTypeImplementingInterface(), 123);
            }
        };
    }

    public static function createParentInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType {
            protected const NAME = 'Bar';

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                    ),
                ]);
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(InterfaceTypeTest::getTypeImplementingInterface(), 123);
            }
        };
    }

    public static function getTypeMissingField() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeFieldTypeMismatch() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Container\Container::Boolean()->notNull(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argument',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argumentNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int()->notNull(),
                            ),
                        ]),
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeFieldTypeMismatchCovariance() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Container\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argument',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argumentNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int()->notNull(),
                            ),
                        ]),
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeMissingArgument() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argument',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argumentNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int()->notNull(),
                            ),
                        ]),
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeArgumentTypeMismatch() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argument',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argumentNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Boolean()->notNull(),
                            ),
                        ]),
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeArgumentTypeMismatchContravariance() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argument',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int()->notNull(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argumentNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int()->notNull(),
                            ),
                        ]),
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeWithoutInterface() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeImplementingInterface() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Xyz';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argument',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int(),
                            ),
                        ]),
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'argumentNotNull',
                        \Graphpinator\Container\Container::Int()->notNull(),
                        static function () : void {
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            new \Graphpinator\Argument\Argument(
                                'argumentName',
                                \Graphpinator\Container\Container::Int()->notNull(),
                            ),
                        ]),
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeImplementingParentInterface() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Zzz';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Utils\InterfaceSet([
                        InterfaceTypeTest::createParentInterface(),
                    ]),
                );
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                        static function () : void {
                        },
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public function testSimple() : void
    {
        $interface = self::createInterface();
        $parentInterface = self::createParentInterface();

        self::assertArrayHasKey('Bar', $interface->getInterfaces());
        self::assertSame('Bar', $interface->getInterfaces()->offsetGet('Bar')->getName());

        self::assertTrue($interface->isInstanceOf($interface));
        self::assertTrue($interface->isInstanceOf(new \Graphpinator\Type\NotNullType($interface)));
        self::assertTrue($interface->isInstanceOf($parentInterface));
        self::assertTrue($interface->isInstanceOf(new \Graphpinator\Type\NotNullType($parentInterface)));
        self::assertFalse($parentInterface->isInstanceOf($interface));
        self::assertFalse($parentInterface->isInstanceOf(new \Graphpinator\Type\NotNullType($interface)));
        self::assertFalse($interface->isImplementedBy(self::getTypeWithoutInterface()));
        self::assertFalse($interface->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTypeWithoutInterface())));
        self::assertFalse($parentInterface->isImplementedBy(self::getTypeWithoutInterface()));
        self::assertFalse($parentInterface->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTypeWithoutInterface())));
        self::assertTrue($interface->isImplementedBy(self::getTypeImplementingInterface()));
        self::assertTrue($interface->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTypeImplementingInterface())));
        self::assertTrue($parentInterface->isImplementedBy(self::getTypeImplementingInterface()));
        self::assertTrue($parentInterface->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTypeImplementingInterface())));
        self::assertFalse($interface->isImplementedBy(self::getTypeImplementingParentInterface()));
        self::assertFalse($interface->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTypeImplementingParentInterface())));
        self::assertTrue($parentInterface->isImplementedBy(self::getTypeImplementingParentInterface()));
        self::assertTrue($parentInterface->isImplementedBy(new \Graphpinator\Type\NotNullType(self::getTypeImplementingParentInterface())));
    }

    public function testMissingField() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceContractMissingField::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Type\InterfaceContractMissingField::MESSAGE);

        self::getTypeMissingField()->getFields();
    }

    public function testIncompatibleFieldType() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceContractFieldTypeMismatch::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Type\InterfaceContractFieldTypeMismatch::MESSAGE);

        self::getTypeFieldTypeMismatch()->getFields();
    }

    public function testIncompatibleFieldTypeContravariance() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceContractFieldTypeMismatch::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Type\InterfaceContractFieldTypeMismatch::MESSAGE);

        self::getTypeFieldTypeMismatchCovariance()->getFields();
    }

    public function testMissingArgument() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceContractMissingArgument::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Type\InterfaceContractMissingArgument::MESSAGE);

        self::getTypeMissingArgument()->getFields();
    }

    public function testIncompatibleArgumentType() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceContractArgumentTypeMismatch::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Type\InterfaceContractArgumentTypeMismatch::MESSAGE);

        self::getTypeArgumentTypeMismatch()->getFields();
    }

    public function testIncompatibleArgumentTypeContravariance() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InterfaceContractArgumentTypeMismatch::class);
        $this->expectExceptionMessage(\Graphpinator\Exception\Type\InterfaceContractArgumentTypeMismatch::MESSAGE);

        self::getTypeArgumentTypeMismatchContravariance()->getFields();
    }
}
