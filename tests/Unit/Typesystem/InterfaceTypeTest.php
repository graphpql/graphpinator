<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Typesystem;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Exception\InterfaceContractArgumentTypeMismatch;
use Graphpinator\Typesystem\Exception\InterfaceContractFieldTypeMismatch;
use Graphpinator\Typesystem\Exception\InterfaceContractMissingArgument;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Typesystem\Field\FieldSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\InterfaceSet;
use Graphpinator\Typesystem\InterfaceType;
use Graphpinator\Typesystem\Type;
use Graphpinator\Value\TypeIntermediateValue;
use PHPUnit\Framework\TestCase;

final class InterfaceTypeTest extends TestCase
{
    public static function createInterface() : InterfaceType
    {
        return new class extends InterfaceType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct(
                    new InterfaceSet([
                        InterfaceTypeTest::createParentInterface(),
                    ]),
                );
            }

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                return new TypeIntermediateValue(InterfaceTypeTest::getTypeImplementingInterface(), 123);
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field(
                        'field',
                        Container::Int(),
                    ),
                    new Field(
                        'fieldNotNull',
                        Container::Int()->notNull(),
                    ),
                    Field::create(
                        'fieldArg',
                        Container::Int()->notNull(),
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int(),
                        ),
                    ])),
                    Field::create(
                        'fieldArgNotNull',
                        Container::Int()->notNull(),
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function createParentInterface() : InterfaceType
    {
        return new class extends InterfaceType {
            protected const NAME = 'Bar';

            public function createResolvedValue($rawValue) : TypeIntermediateValue
            {
                return new TypeIntermediateValue(InterfaceTypeTest::getTypeImplementingInterface(), 123);
            }

            protected function getFieldDefinition() : FieldSet
            {
                return new FieldSet([
                    new Field(
                        'field',
                        Container::Int(),
                    ),
                ]);
            }
        };
    }

    public static function getTypeFieldTypeMismatch() : Type
    {
        return new class extends Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'field',
                        Container::Int(),
                        static function () : void {
                        },
                    ),
                    new ResolvableField(
                        'fieldNotNull',
                        Container::Boolean()->notNull(),
                        static function () : void {
                        },
                    ),
                    ResolvableField::create(
                        'fieldArg',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int(),
                        ),
                    ])),
                    ResolvableField::create(
                        'fieldArgNotNull',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeFieldTypeMismatchCovariance() : Type
    {
        return new class extends Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'field',
                        Container::Int(),
                        static function () : void {
                        },
                    ),
                    new ResolvableField(
                        'fieldNotNull',
                        Container::Int(),
                        static function () : void {
                        },
                    ),
                    ResolvableField::create(
                        'fieldArg',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int(),
                        ),
                    ])),
                    ResolvableField::create(
                        'fieldArgNotNull',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeMissingArgument() : Type
    {
        return new class extends Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'field',
                        Container::Int(),
                        static function () : void {
                        },
                    ),
                    new ResolvableField(
                        'fieldNotNull',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    new ResolvableField(
                        'fieldArg',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    ResolvableField::create(
                        'fieldArgNotNull',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeArgumentTypeMismatch() : Type
    {
        return new class extends Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'field',
                        Container::Int(),
                        static function () : void {
                        },
                    ),
                    new ResolvableField(
                        'fieldNotNull',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    ResolvableField::create(
                        'fieldArg',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int(),
                        ),
                    ])),
                    ResolvableField::create(
                        'fieldArgNotNull',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Boolean()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeArgumentTypeMismatchContravariance() : Type
    {
        return new class extends Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'field',
                        Container::Int(),
                        static function () : void {
                        },
                    ),
                    new ResolvableField(
                        'fieldNotNull',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    ResolvableField::create(
                        'fieldArg',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int()->notNull(),
                        ),
                    ])),
                    ResolvableField::create(
                        'fieldArgNotNull',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeWithoutInterface() : Type
    {
        return new class extends Type {
            protected const NAME = 'Abc';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet();
            }
        };
    }

    public static function getTypeImplementingInterface() : Type
    {
        return new class extends Type {
            protected const NAME = 'Xyz';

            public function __construct()
            {
                parent::__construct(
                    new InterfaceSet([
                        InterfaceTypeTest::createInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'field',
                        Container::Int(),
                        static function () : void {
                        },
                    ),
                    new ResolvableField(
                        'fieldNotNull',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    ),
                    ResolvableField::create(
                        'fieldArg',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int(),
                        ),
                    ])),
                    ResolvableField::create(
                        'fieldArgNotNull',
                        Container::Int()->notNull(),
                        static function () : void {
                        },
                    )->setArguments(new ArgumentSet([
                        new Argument(
                            'argName',
                            Container::Int()->notNull(),
                        ),
                    ])),
                ]);
            }
        };
    }

    public static function getTypeImplementingParentInterface() : Type
    {
        return new class extends Type {
            protected const NAME = 'Zzz';

            public function __construct()
            {
                parent::__construct(
                    new InterfaceSet([
                        InterfaceTypeTest::createParentInterface(),
                    ]),
                );
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'field',
                        Container::Int(),
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

        self::assertArrayHasKey('Bar', $interface->getInterfaces());
        self::assertSame('Bar', $interface->getInterfaces()->offsetGet('Bar')->getName());
    }

    public function testIncompatibleFieldType() : void
    {
        $this->expectException(InterfaceContractFieldTypeMismatch::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Foo" - field "fieldNotNull" does not have a compatible type.');

        self::getTypeFieldTypeMismatch()->getFields();
    }

    public function testIncompatibleFieldTypeCovariance() : void
    {
        $this->expectException(InterfaceContractFieldTypeMismatch::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Foo" - field "fieldNotNull" does not have a compatible type.');

        self::getTypeFieldTypeMismatchCovariance()->getFields();
    }

    public function testMissingArgument() : void
    {
        $this->expectException(InterfaceContractMissingArgument::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Foo" - argument "argName" on field "fieldArg" is missing.');

        self::getTypeMissingArgument()->getFields();
    }

    public function testIncompatibleArgumentType() : void
    {
        $this->expectException(InterfaceContractArgumentTypeMismatch::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Foo" - '
            . 'argument "argName" on field "fieldArgNotNull" does not have a compatible type.');

        self::getTypeArgumentTypeMismatch()->getFields();
    }

    public function testIncompatibleArgumentTypeContravariance() : void
    {
        $this->expectException(InterfaceContractArgumentTypeMismatch::class);
        $this->expectExceptionMessage('Type "Abc" does not satisfy interface "Foo" - '
            . 'argument "argName" on field "fieldArg" does not have a compatible type.');

        self::getTypeArgumentTypeMismatchContravariance()->getFields();
    }
}
