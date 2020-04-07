<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class TestSchema
{
    use \Nette\StaticClass;

    public static function getTypeResolver() : \Graphpinator\Type\Resolver
    {
        return new class implements \Graphpinator\Type\Resolver
        {
            public function getType(string $name): \Graphpinator\Type\Contract\NamedDefinition
            {
                switch ($name) {
                    case 'Query':
                        return TestSchema::getQuery();
                    case 'Abc':
                        return TestSchema::getTypeAbc();
                    case 'Xyz':
                        return TestSchema::getTypeXyz();
                    case 'TestInterface':
                        return TestSchema::getInterface();
                    case 'TestUnion':
                        return TestSchema::getUnion();
                    case 'TestInput':
                        return TestSchema::getInput();
                    case 'TestInnerInput':
                        return TestSchema::getInnerInput();
                    case 'Int':
                        return \Graphpinator\Type\Scalar\ScalarType::Int();
                    case 'Float':
                        return \Graphpinator\Type\Scalar\ScalarType::Float();
                    case 'String':
                        return \Graphpinator\Type\Scalar\ScalarType::String();
                    case 'Boolean':
                        return \Graphpinator\Type\Scalar\ScalarType::Boolean();
                    default:
                        throw new \Exception('Cannot resolve type.');
                }
            }

            public function getSchema(): \Graphpinator\Type\Schema
            {
                return new \Graphpinator\Type\Schema(TestSchema::getQuery());
            }
        };
    }

    public static function getQuery() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Query';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField('field0', TestSchema::getUnion(), function () {
                        return \Graphpinator\Resolver\FieldResult::fromRaw(TestSchema::getTypeAbc(), null);
                    })
                ]));
            }
        };
    }

    public static function getTypeAbc() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField('field1', TestSchema::getInterface(),
                        function ($parent, \Graphpinator\Normalizer\ArgumentValueSet $args) {
                            $object = new \stdClass();

                            if ($args['arg2']->getRawValue() === null) {
                                $object->name = 'Test ' . $args['arg1']->getRawValue();
                            } else {
                                $objectVal = $args['arg2']->getRawValue();
                                $str = '';

                                \array_walk_recursive($objectVal, function ($item, $key) use (&$str) {
                                    $str .= $key . ': ' . $item . '; ';
                                });

                                $object->name = 'Test input: ' . $str;
                            }

                            return \Graphpinator\Resolver\FieldResult::fromRaw(TestSchema::getTypeXyz(), $object);
                    }, new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument('arg1', \Graphpinator\Type\Scalar\ScalarType::Int(), 123),
                        new \Graphpinator\Argument\Argument('arg2', TestSchema::getInput()),
                    ]))
                ]));
            }
        };
    }

    public static function getTypeXyz() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Xyz';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField('name', \Graphpinator\Type\Scalar\ScalarType::String(), function (\stdClass $parent) {
                        return $parent->name;
                    })
                ]), new \Graphpinator\Type\Utils\InterfaceSet([TestSchema::getInterface()]));
            }
        };
    }

    public static function getInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'TestInput';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument('name', \Graphpinator\Type\Scalar\ScalarType::String()->notNull()),
                    new \Graphpinator\Argument\Argument('inner', TestSchema::getInnerInput()),
                    new \Graphpinator\Argument\Argument('innerList', TestSchema::getInnerInput()->notNullList()),
                    new \Graphpinator\Argument\Argument('innerNotNull', TestSchema::getInnerInput()->notNull()),
                ]));
            }
        };
    }

    public static function getInnerInput() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected const NAME = 'TestInnerInput';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument('name', \Graphpinator\Type\Scalar\ScalarType::String()->notNull()),
                    new \Graphpinator\Argument\Argument('number', \Graphpinator\Type\Scalar\ScalarType::Int()->notNullList()),
                    new \Graphpinator\Argument\Argument('bool', \Graphpinator\Type\Scalar\ScalarType::Boolean()),
                ]));
            }
        };
    }

    public static function getInterface() : \Graphpinator\Type\InterfaceType
    {
        return new class extends \Graphpinator\Type\InterfaceType
        {
            protected const NAME = 'TestInterface';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field('name', \Graphpinator\Type\Scalar\ScalarType::String()),
                ]));
            }
        };
    }

    public static function getUnion() : \Graphpinator\Type\UnionType
    {
        return new class extends \Graphpinator\Type\UnionType
        {
            protected const NAME = 'TestUnion';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Type\Utils\ConcreteSet([
                    TestSchema::getTypeAbc(),
                    TestSchema::getTypeXyz(),
                ]));
            }
        };
    }
}