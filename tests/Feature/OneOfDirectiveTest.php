<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Common\Path;
use Graphpinator\Normalizer\VariableValueSet;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Exception\OneOfDirectiveNotSatisfied;
use Graphpinator\Typesystem\Exception\OneOfInputInvalidFields;
use Graphpinator\Typesystem\InputType;
use Graphpinator\Value\ConvertRawValueVisitor;
use Graphpinator\Value\InputValue;
use PHPUnit\Framework\TestCase;

final class OneOfDirectiveTest extends TestCase
{
    public static function createInputWithDefaults() : InputType
    {
        return new class extends InputType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(Container::directiveOneOf());
            }

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create('one', Container::Int()),
                    Argument::create('two', Container::Int())
                        ->setDefaultValue(1),
                ]);
            }
        };
    }

    public static function createInputWithNotNull() : InputType
    {
        return new class extends InputType {
            protected const NAME = 'Bar';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(Container::directiveOneOf());
            }

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create('one', Container::Int()),
                    Argument::create('two', Container::Int()->notNull()),
                ]);
            }
        };
    }

    public static function createInput() : InputType
    {
        return new class extends InputType {
            protected const NAME = 'Baz';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(Container::directiveOneOf());
            }

            protected function getFieldDefinition() : ArgumentSet
            {
                return new ArgumentSet([
                    Argument::create('one', Container::Int()),
                    Argument::create('two', Container::Int()),
                ]);
            }
        };
    }

    public static function invalidValueDataProvider() : array
    {
        return [
            [(object) ['one' => 1, 'two' => null], OneOfDirectiveNotSatisfied::class],
            [(object) ['one' => null, 'two' => 1], OneOfDirectiveNotSatisfied::class],
            [(object) ['one' => 1, 'two' => 2], OneOfDirectiveNotSatisfied::class],
            [(object) ['one' => null, 'two' => null], OneOfDirectiveNotSatisfied::class],
            [(object) ['one' => null], OneOfDirectiveNotSatisfied::class],
            [(object) ['two' => null], OneOfDirectiveNotSatisfied::class],
        ];
    }

    public function testOneOfWithDefault() : void
    {
        $this->expectException(OneOfInputInvalidFields::class);

        self::createInputWithDefaults()->getArguments();
    }

    public function testOneOfWithNotNull() : void
    {
        $this->expectException(OneOfInputInvalidFields::class);

        self::createInputWithNotNull()->getArguments();
    }

    /**
     * @dataProvider invalidValueDataProvider
     */
    public function testCreateValueInvalid(\stdClass $rawValue, string $exception) : void
    {
        $this->expectException($exception);

        $value = self::createInput()->accept(new ConvertRawValueVisitor($rawValue, new Path()));
        \assert($value instanceof InputValue);
        $value->applyVariables(new VariableValueSet([]));
    }

    public function testCreateValue() : void
    {
        $rawValueOne = (object) ['one' => 1];
        $rawValueTwo = (object) ['two' => 1];

        $value = self::createInput()->accept(new ConvertRawValueVisitor($rawValueOne, new Path()));
        \assert($value instanceof InputValue);
        $value->applyVariables(new VariableValueSet([]));

        self::assertEquals($value->getRawValue(true), $rawValueOne);
        self::assertNotEquals($value->getRawValue(true), $rawValueTwo);

        $value = self::createInput()->accept(new ConvertRawValueVisitor($rawValueTwo, new Path()));
        \assert($value instanceof InputValue);
        $value->applyVariables(new VariableValueSet([]));

        self::assertEquals($value->getRawValue(true), $rawValueTwo);
        self::assertNotEquals($value->getRawValue(true), $rawValueOne);
    }
}
