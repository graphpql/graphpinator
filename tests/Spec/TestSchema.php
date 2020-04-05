<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class TestSchema
{
    use \Nette\StaticClass;

    public static function getTypeResolver() : \Graphpinator\DI\TypeResolver
    {
        return new class implements \Graphpinator\DI\TypeResolver
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
                        return \Graphpinator\Field\ResolveResult::fromRaw(TestSchema::getTypeAbc(), null);
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
                        function ($parent, \Graphpinator\Value\ArgumentValueSet $args) {
                        $object = new \stdClass();
                        $object->name = $args['arg1']->getRawValue() === 123 ? 'Test name' : 'Test argument';

                        return \Graphpinator\Field\ResolveResult::fromRaw(TestSchema::getTypeXyz(), $object);
                    }, new \Graphpinator\Argument\ArgumentSet([
                        new \Graphpinator\Argument\Argument('arg1', \Graphpinator\Type\Scalar\ScalarType::Int(), 123)
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