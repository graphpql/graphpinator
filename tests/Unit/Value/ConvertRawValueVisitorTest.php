<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Value;

use Graphpinator\Common\Path;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\EnumItem\EnumItem;
use Graphpinator\Typesystem\EnumItem\EnumItemSet;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Value\EnumValue;
use Graphpinator\Value\Exception\InvalidValue;
use Graphpinator\Value\Exception\ValueCannotBeNull;
use Graphpinator\Value\InputValue;
use Graphpinator\Value\ListInputedValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ScalarValue;
use Graphpinator\Value\Visitor\ConvertRawValueVisitor;
use PHPUnit\Framework\TestCase;

final class ConvertRawValueVisitorTest extends TestCase
{
    private static ?ScalarType $stringType = null;
    private static ?ScalarType $intType = null;
    private static ?EnumType $enum = null;
    public static ?InputType $input = null;

    public static function setUpBeforeClass() : void
    {
        self::$stringType = Container::String();
        self::$intType = Container::Int();

        self::$enum = new class extends EnumType {
            protected const NAME = 'TestEnum';

            public function __construct()
            {
                parent::__construct($this->getEnumItems());
            }

            protected function getEnumItems() : EnumItemSet
            {
                return new EnumItemSet([
                    new EnumItem('VALUE1'),
                    new EnumItem('VALUE2'),
                ]);
            }
        };

        self::$input = new class extends InputType {
            protected const NAME = 'TestInput';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument('name', Container::String()),
                    new Argument('age', Container::Int()),
                ]);
            }
        };
    }

    public function testScalarWithValue() : void
    {
        $visitor = new ConvertRawValueVisitor('test', new Path());
        $result = self::$stringType->accept($visitor);

        self::assertInstanceOf(ScalarValue::class, $result);
        self::assertSame('test', $result->getRawValue());
    }

    public function testScalarWithNull() : void
    {
        $visitor = new ConvertRawValueVisitor(null, new Path());
        $result = self::$stringType->accept($visitor);

        self::assertInstanceOf(NullValue::class, $result);
    }

    public function testEnumWithValue() : void
    {
        $visitor = new ConvertRawValueVisitor('VALUE1', new Path());
        $result = self::$enum->accept($visitor);

        self::assertInstanceOf(EnumValue::class, $result);
        self::assertSame('VALUE1', $result->getRawValue());
    }

    public function testEnumWithNull() : void
    {
        $visitor = new ConvertRawValueVisitor(null, new Path());
        $result = self::$enum->accept($visitor);

        self::assertInstanceOf(NullValue::class, $result);
    }

    public function testInputWithValue() : void
    {
        $visitor = new ConvertRawValueVisitor(
            (object) ['name' => 'John', 'age' => 30],
            new Path(),
        );
        $result = self::$input->accept($visitor);

        self::assertInstanceOf(InputValue::class, $result);
    }

    public function testInputWithNull() : void
    {
        $visitor = new ConvertRawValueVisitor(null, new Path());
        $result = self::$input->accept($visitor);

        self::assertInstanceOf(NullValue::class, $result);
    }

    public function testInputWithInvalidValue() : void
    {
        $this->expectException(InvalidValue::class);

        $visitor = new ConvertRawValueVisitor('not an object', new Path());
        self::$input->accept($visitor);
    }

    public function testNotNullWithValue() : void
    {
        $notNull = new NotNullType(self::$stringType);
        $visitor = new ConvertRawValueVisitor('test', new Path());
        $result = $notNull->accept($visitor);

        self::assertInstanceOf(ScalarValue::class, $result);
    }

    public function testNotNullWithNull() : void
    {
        $this->expectException(ValueCannotBeNull::class);

        $notNull = new NotNullType(self::$stringType);
        $visitor = new ConvertRawValueVisitor(null, new Path());
        $notNull->accept($visitor);
    }

    public function testListWithArray() : void
    {
        $list = new ListType(self::$intType);
        $visitor = new ConvertRawValueVisitor([1, 2, 3], new Path());
        $result = $list->accept($visitor);

        self::assertInstanceOf(ListInputedValue::class, $result);
        self::assertCount(3, $result->getRawValue());
    }

    public function testListWithSingleValue() : void
    {
        $list = new ListType(self::$intType);
        $visitor = new ConvertRawValueVisitor(123, new Path());
        $result = $list->accept($visitor);

        self::assertInstanceOf(ListInputedValue::class, $result);
        self::assertCount(1, $result->getRawValue());
    }

    public function testListWithNull() : void
    {
        $list = new ListType(self::$intType);
        $visitor = new ConvertRawValueVisitor(null, new Path());
        $result = $list->accept($visitor);

        self::assertInstanceOf(NullValue::class, $result);
    }

    public function testNestedList() : void
    {
        $list = new ListType(new ListType(self::$intType));
        $visitor = new ConvertRawValueVisitor([[1, 2], [3, 4]], new Path());
        $result = $list->accept($visitor);

        self::assertInstanceOf(ListInputedValue::class, $result);
        self::assertCount(2, $result->getRawValue());
    }

    public function testNotNullListWithValue() : void
    {
        $notNullList = new NotNullType(new ListType(self::$intType));
        $visitor = new ConvertRawValueVisitor([1, 2, 3], new Path());
        $result = $notNullList->accept($visitor);

        self::assertInstanceOf(ListInputedValue::class, $result);
    }

    public function testNotNullListWithNull() : void
    {
        $this->expectException(ValueCannotBeNull::class);

        $notNullList = new NotNullType(new ListType(self::$intType));
        $visitor = new ConvertRawValueVisitor(null, new Path());
        $notNullList->accept($visitor);
    }

    public function testListOfNotNull() : void
    {
        $listOfNotNull = new ListType(new NotNullType(self::$intType));
        $visitor = new ConvertRawValueVisitor([1, 2, 3], new Path());
        $result = $listOfNotNull->accept($visitor);

        self::assertInstanceOf(ListInputedValue::class, $result);
    }

    public function testListOfNotNullWithNullItem() : void
    {
        $this->expectException(ValueCannotBeNull::class);

        $listOfNotNull = new ListType(new NotNullType(self::$intType));
        $visitor = new ConvertRawValueVisitor([1, null, 3], new Path());
        $listOfNotNull->accept($visitor);
    }

    public function testComplexInput() : void
    {
        $complexInput = new class extends InputType {
            protected const NAME = 'ComplexInput';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument('items', new ListType(Container::Int())),
                    new Argument('nested', ConvertRawValueVisitorTest::$input),
                ]);
            }
        };

        $visitor = new ConvertRawValueVisitor(
            (object) [
                'items' => [1, 2, 3],
                'nested' => (object) ['name' => 'Test', 'age' => 25],
            ],
            new Path(),
        );
        $result = $complexInput->accept($visitor);

        self::assertInstanceOf(InputValue::class, $result);
    }
}
