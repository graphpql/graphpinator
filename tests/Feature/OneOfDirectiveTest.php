<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class OneOfDirectiveTest extends \PHPUnit\Framework\TestCase
{
    public static function createInputWithDefaults() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType {
            protected const NAME = 'Foo';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(\Graphpinator\Typesystem\Container::directiveOneOf());
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create('one', \Graphpinator\Typesystem\Container::Int()),
                    \Graphpinator\Typesystem\Argument\Argument::create('two', \Graphpinator\Typesystem\Container::Int())
                        ->setDefaultValue(1),
                ]);
            }
        };
    }

    public static function createInputWithNotNull() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType {
            protected const NAME = 'Bar';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(\Graphpinator\Typesystem\Container::directiveOneOf());
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create('one', \Graphpinator\Typesystem\Container::Int()),
                    \Graphpinator\Typesystem\Argument\Argument::create('two', \Graphpinator\Typesystem\Container::Int()->notNull()),
                ]);
            }
        };
    }

    public static function createInput() : \Graphpinator\Typesystem\InputType
    {
        return new class extends \Graphpinator\Typesystem\InputType {
            protected const NAME = 'Baz';

            public function __construct()
            {
                parent::__construct();

                $this->addDirective(\Graphpinator\Typesystem\Container::directiveOneOf());
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet([
                    \Graphpinator\Typesystem\Argument\Argument::create('one', \Graphpinator\Typesystem\Container::Int()),
                    \Graphpinator\Typesystem\Argument\Argument::create('two', \Graphpinator\Typesystem\Container::Int()),
                ]);
            }
        };
    }

    public static function invalidValueDataProvider() : array
    {
        return [
            [(object) ['one' => 1, 'two' => null], \Graphpinator\Typesystem\Exception\OneOfDirectiveNotSatisfied::class],
            [(object) ['one' => null, 'two' => 1], \Graphpinator\Typesystem\Exception\OneOfDirectiveNotSatisfied::class],
            [(object) ['one' => 1, 'two' => 2], \Graphpinator\Typesystem\Exception\OneOfDirectiveNotSatisfied::class],
            [(object) ['one' => null, 'two' => null], \Graphpinator\Typesystem\Exception\OneOfDirectiveNotSatisfied::class],
            [(object) ['one' => null], \Graphpinator\Typesystem\Exception\OneOfDirectiveNotSatisfied::class],
            [(object) ['two' => null], \Graphpinator\Typesystem\Exception\OneOfDirectiveNotSatisfied::class],
        ];
    }

    public function testOneOfWithDefault() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\OneOfInputInvalidFields::class);

        self::createInputWithDefaults()->getArguments();
    }

    public function testOneOfWithNotNull() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\OneOfInputInvalidFields::class);

        self::createInputWithNotNull()->getArguments();
    }

    /**
     * @dataProvider invalidValueDataProvider
     */
    public function testCreateValueInvalid(\stdClass $rawValue, string $exception) : void
    {
        $this->expectException($exception);

        $value = self::createInput()->accept(new \Graphpinator\Value\ConvertRawValueVisitor($rawValue, new \Graphpinator\Common\Path()));
        \assert($value instanceof \Graphpinator\Value\InputValue);
        $value->applyVariables(new \Graphpinator\Normalizer\VariableValueSet([]));
    }

    public function testCreateValue() : void
    {
        $rawValueOne = (object) ['one' => 1];
        $rawValueTwo = (object) ['two' => 1];

        $value = self::createInput()->accept(new \Graphpinator\Value\ConvertRawValueVisitor($rawValueOne, new \Graphpinator\Common\Path()));
        \assert($value instanceof \Graphpinator\Value\InputValue);
        $value->applyVariables(new \Graphpinator\Normalizer\VariableValueSet([]));

        self::assertEquals($value->getRawValue(true), $rawValueOne);
        self::assertNotEquals($value->getRawValue(true), $rawValueTwo);

        $value = self::createInput()->accept(new \Graphpinator\Value\ConvertRawValueVisitor($rawValueTwo, new \Graphpinator\Common\Path()));
        \assert($value instanceof \Graphpinator\Value\InputValue);
        $value->applyVariables(new \Graphpinator\Normalizer\VariableValueSet([]));

        self::assertEquals($value->getRawValue(true), $rawValueTwo);
        self::assertNotEquals($value->getRawValue(true), $rawValueOne);
    }
}
