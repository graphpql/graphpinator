<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem;

final class InterfaceTypeTest extends \PHPUnit\Framework\TestCase
{
    public static function createInterface() : \Graphpinator\Typesystem\InterfaceType
    {
        return new class extends \Graphpinator\Typesystem\InterfaceType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Typesystem\InterfaceSet([
                        InterfaceTypeTest::createParentInterface(),
                    ]),
                );
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(InterfaceTypeTest::getTypeImplementingInterface(), 123);
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                    ),
                    new \Graphpinator\Field\Field(
                        'fieldNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                    ),
                    \Graphpinator\Field\Field::create(
                        'fieldArg',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int(),
                        ),
                    ])),
                    \Graphpinator\Field\Field::create(
                        'fieldArgNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function createParentInterface() : \Graphpinator\Typesystem\InterfaceType
    {
        return new class extends \Graphpinator\Typesystem\InterfaceType {
            protected const NAME = 'Bar';

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(InterfaceTypeTest::getTypeImplementingInterface(), 123);
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function getTypeFieldTypeMismatch() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Typesystem\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Typesystem\Container::Boolean()->notNull(),
                        static function () : void {
                        },
                    ),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArg',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArgNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeFieldTypeMismatchCovariance() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Typesystem\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function () : void {
                        },
                    ),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArg',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArgNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeMissingArgument() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Typesystem\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldArg',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArgNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeArgumentTypeMismatch() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Typesystem\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArg',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArgNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Boolean()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeArgumentTypeMismatchContravariance() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Typesystem\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArg',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int()->notNull(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArgNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeWithoutInterface() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Abc';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet();
            }
        };
    }

    public static function getTypeImplementingInterface() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Xyz';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Typesystem\InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function () : void {
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'fieldNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArg',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int(),
                        ),
                    ])),
                    \Graphpinator\Field\ResolvableField::create(
                        'fieldArgNotNull',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument(
                            'argName',
                            \Graphpinator\Typesystem\Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeImplementingParentInterface() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Zzz';

            public function __construct()
            {
                parent::__construct(
                    new \Graphpinator\Typesystem\InterfaceSet([
                        InterfaceTypeTest::createParentInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function () : void {
                        },
                    ),
                ]);
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
        self::assertTrue($interface->isInstanceOf(new \Graphpinator\Typesystem\NotNullType($interface)));
        self::assertTrue($interface->isInstanceOf($parentInterface));
        self::assertTrue($interface->isInstanceOf(new \Graphpinator\Typesystem\NotNullType($parentInterface)));
        self::assertFalse($parentInterface->isInstanceOf($interface));
        self::assertFalse($parentInterface->isInstanceOf(new \Graphpinator\Typesystem\NotNullType($interface)));
        self::assertFalse($interface->isImplementedBy(self::getTypeWithoutInterface()));
        self::assertFalse($interface->isImplementedBy(new \Graphpinator\Typesystem\NotNullType(self::getTypeWithoutInterface())));
        self::assertFalse($parentInterface->isImplementedBy(self::getTypeWithoutInterface()));
        self::assertFalse($parentInterface->isImplementedBy(new \Graphpinator\Typesystem\NotNullType(self::getTypeWithoutInterface())));
        self::assertTrue($interface->isImplementedBy(self::getTypeImplementingInterface()));
        self::assertTrue($interface->isImplementedBy(new \Graphpinator\Typesystem\NotNullType(self::getTypeImplementingInterface())));
        self::assertTrue($parentInterface->isImplementedBy(self::getTypeImplementingInterface()));
        self::assertTrue($parentInterface->isImplementedBy(new \Graphpinator\Typesystem\NotNullType(self::getTypeImplementingInterface())));
        self::assertFalse($interface->isImplementedBy(self::getTypeImplementingParentInterface()));
        self::assertFalse($interface->isImplementedBy(new \Graphpinator\Typesystem\NotNullType(self::getTypeImplementingParentInterface())));
        self::assertTrue($parentInterface->isImplementedBy(self::getTypeImplementingParentInterface()));
        self::assertTrue($parentInterface->isImplementedBy(new \Graphpinator\Typesystem\NotNullType(self::getTypeImplementingParentInterface())));
    }

    public function testIncompatibleFieldType() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\InterfaceContractFieldTypeMismatch::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Foo" - field "fieldNotNull" does not have a compatible type.');

        self::getTypeFieldTypeMismatch()->getFields();
    }

    public function testIncompatibleFieldTypeContravariance() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\InterfaceContractFieldTypeMismatch::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Foo" - field "fieldNotNull" does not have a compatible type.');

        self::getTypeFieldTypeMismatchCovariance()->getFields();
    }

    public function testMissingArgument() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\InterfaceContractMissingArgument::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Foo" - argument "argName" on field "fieldArg" is missing.');

        self::getTypeMissingArgument()->getFields();
    }

    public function testIncompatibleArgumentType() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\InterfaceContractArgumentTypeMismatch::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Foo" - '
            . 'argument "argName" on field "fieldArgNotNull" does not have a compatible type.');

        self::getTypeArgumentTypeMismatch()->getFields();
    }

    public function testIncompatibleArgumentTypeContravariance() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\InterfaceContractArgumentTypeMismatch::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Foo" - '
            . 'argument "argName" on field "fieldArg" does not have a compatible type.');

        self::getTypeArgumentTypeMismatchContravariance()->getFields();
    }
}
