<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Value;

use Graphpinator\Common\Path;
use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\Exception\UnknownArgument;
use Graphpinator\Normalizer\Exception\UnknownVariable;
use Graphpinator\Normalizer\Exception\VariableInConstContext;
use Graphpinator\Normalizer\Variable\Variable;
use Graphpinator\Normalizer\Variable\VariableSet;
use Graphpinator\Parser\Value\EnumLiteral;
use Graphpinator\Parser\Value\ListVal;
use Graphpinator\Parser\Value\Literal;
use Graphpinator\Parser\Value\ObjectVal;
use Graphpinator\Parser\Value\VariableRef;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\EnumItem\EnumItem;
use Graphpinator\Typesystem\EnumItem\EnumItemSet;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Value\EnumValue;
use Graphpinator\Value\Exception\InvalidValue;
use Graphpinator\Value\Exception\ValueCannotBeNull;
use Graphpinator\Value\InputValue;
use Graphpinator\Value\ListInputedValue;
use Graphpinator\Value\NullValue;
use Graphpinator\Value\ScalarValue;
use Graphpinator\Value\VariableValue;
use Graphpinator\Value\Visitor\ConvertParserValueVisitor;
use PHPUnit\Framework\TestCase;

final class ConvertParserValueVisitorTest extends TestCase
{
    public static ?InputType $input = null;
    private static ?EnumType $enum = null;

    public static function setUpBeforeClass() : void
    {
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

    public function testLiteralString() : void
    {
        $literal = new Literal('test');
        $visitor = new ConvertParserValueVisitor(Container::String(), null, new Path());
        $result = $literal->accept($visitor);

        self::assertInstanceOf(ScalarValue::class, $result);
        self::assertSame('test', $result->getRawValue());
    }

    public function testLiteralInt() : void
    {
        $literal = new Literal(123);
        $visitor = new ConvertParserValueVisitor(Container::Int(), null, new Path());
        $result = $literal->accept($visitor);

        self::assertInstanceOf(ScalarValue::class, $result);
        self::assertSame(123, $result->getRawValue());
    }

    public function testLiteralNull() : void
    {
        $literal = new Literal(null);
        $visitor = new ConvertParserValueVisitor(Container::String(), null, new Path());
        $result = $literal->accept($visitor);

        self::assertInstanceOf(NullValue::class, $result);
    }

    public function testEnumLiteral() : void
    {
        $enumLiteral = new EnumLiteral('VALUE1');
        $visitor = new ConvertParserValueVisitor(self::$enum, null, new Path());
        $result = $enumLiteral->accept($visitor);

        self::assertInstanceOf(EnumValue::class, $result);
        self::assertSame('VALUE1', $result->getRawValue());
    }

    public function testEnumLiteralWithNotNull() : void
    {
        $enumLiteral = new EnumLiteral('VALUE1');
        $notNull = new NotNullType(self::$enum);
        $visitor = new ConvertParserValueVisitor($notNull, null, new Path());
        $result = $enumLiteral->accept($visitor);

        self::assertInstanceOf(EnumValue::class, $result);
    }

    public function testEnumLiteralInvalidType() : void
    {
        $this->expectException(InvalidValue::class);

        $enumLiteral = new EnumLiteral('VALUE1');
        $visitor = new ConvertParserValueVisitor(Container::String(), null, new Path());
        $enumLiteral->accept($visitor);
    }

    public function testListVal() : void
    {
        $listVal = new ListVal([
            new Literal(1),
            new Literal(2),
            new Literal(3),
        ]);
        $visitor = new ConvertParserValueVisitor(new ListType(Container::Int()), null, new Path());
        $result = $listVal->accept($visitor);

        self::assertInstanceOf(ListInputedValue::class, $result);
        self::assertCount(3, $result->getRawValue());
    }

    public function testListValWithNotNull() : void
    {
        $listVal = new ListVal([new Literal(1), new Literal(2)]);
        $notNull = new NotNullType(new ListType(Container::Int()));
        $visitor = new ConvertParserValueVisitor($notNull, null, new Path());
        $result = $listVal->accept($visitor);

        self::assertInstanceOf(ListInputedValue::class, $result);
    }

    public function testListValInvalidType() : void
    {
        $this->expectException(InvalidValue::class);

        $listVal = new ListVal([new Literal(1)]);
        $visitor = new ConvertParserValueVisitor(Container::Int(), null, new Path());
        $listVal->accept($visitor);
    }

    public function testObjectVal() : void
    {
        $objectVal = new ObjectVal((object) [
            'name' => new Literal('John'),
            'age' => new Literal(30),
        ]);
        $visitor = new ConvertParserValueVisitor(self::$input, null, new Path());
        $result = $objectVal->accept($visitor);

        self::assertInstanceOf(InputValue::class, $result);
    }

    public function testObjectValWithNotNull() : void
    {
        $objectVal = new ObjectVal((object) [
            'name' => new Literal('John'),
            'age' => new Literal(30),
        ]);
        $notNull = new NotNullType(self::$input);
        $visitor = new ConvertParserValueVisitor($notNull, null, new Path());
        $result = $objectVal->accept($visitor);

        self::assertInstanceOf(InputValue::class, $result);
    }

    public function testObjectValUnknownField() : void
    {
        $this->expectException(UnknownArgument::class);

        $objectVal = new ObjectVal((object) [
            'name' => new Literal('John'),
            'age' => new Literal(30),
            'unknown' => new Literal('field'),
        ]);
        $visitor = new ConvertParserValueVisitor(self::$input, null, new Path());
        $objectVal->accept($visitor);
    }

    public function testObjectValMissingRequiredField() : void
    {
        $this->expectException(ValueCannotBeNull::class);

        $inputWithRequired = new class extends InputType {
            protected const NAME = 'InputWithRequired';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument('required', new NotNullType(Container::String())),
                ]);
            }
        };

        $objectVal = new ObjectVal((object) []);
        $visitor = new ConvertParserValueVisitor($inputWithRequired, null, new Path());
        $objectVal->accept($visitor);
    }

    public function testObjectValInvalidType() : void
    {
        $this->expectException(InvalidValue::class);

        $objectVal = new ObjectVal((object) ['name' => new Literal('test')]);
        $visitor = new ConvertParserValueVisitor(Container::String(), null, new Path());
        $objectVal->accept($visitor);
    }

    public function testVariableRef() : void
    {
        $variable = new Variable('testVar', Container::String(), null, new DirectiveSet());
        $variableSet = new VariableSet([$variable]);
        $variableRef = new VariableRef('testVar');
        $visitor = new ConvertParserValueVisitor(Container::String(), $variableSet, new Path());
        $result = $variableRef->accept($visitor);

        self::assertInstanceOf(VariableValue::class, $result);
    }

    public function testVariableRefUnknownVariable() : void
    {
        $this->expectException(UnknownVariable::class);

        $variableSet = new VariableSet([]);
        $variableRef = new VariableRef('unknownVar');
        $visitor = new ConvertParserValueVisitor(Container::String(), $variableSet, new Path());
        $variableRef->accept($visitor);
    }

    public function testVariableRefInConstContext() : void
    {
        $this->expectException(VariableInConstContext::class);

        $variableRef = new VariableRef('testVar');
        $visitor = new ConvertParserValueVisitor(Container::String(), null, new Path());
        $variableRef->accept($visitor);
    }

    public function testNestedList() : void
    {
        $listVal = new ListVal([
            new ListVal([new Literal(1), new Literal(2)]),
            new ListVal([new Literal(3), new Literal(4)]),
        ]);
        $nestedList = new ListType(new ListType(Container::Int()));
        $visitor = new ConvertParserValueVisitor($nestedList, null, new Path());
        $result = $listVal->accept($visitor);

        self::assertInstanceOf(ListInputedValue::class, $result);
        self::assertCount(2, $result->getRawValue());
    }

    public function testNestedObject() : void
    {
        $nestedInput = new class extends InputType {
            protected const NAME = 'NestedInput';

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    new Argument('outer', ConvertParserValueVisitorTest::$input),
                ]);
            }
        };

        $objectVal = new ObjectVal((object) [
            'outer' => new ObjectVal((object) [
                'name' => new Literal('John'),
                'age' => new Literal(30),
            ]),
        ]);
        $visitor = new ConvertParserValueVisitor($nestedInput, null, new Path());
        $result = $objectVal->accept($visitor);

        self::assertInstanceOf(InputValue::class, $result);
    }
}
