<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Spec;

final class PrintTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        $container = new \Graphpinator\Type\Container\SimpleContainer([], []);

        return [
            [
                \Graphpinator\Type\Container\Container::Int(),
                'scalar Int',
            ],
            [
                $container->introspectionTypeKind(),
                <<<EOL
                """
                Built-in introspection enum.
                """
                enum __TypeKind {
                  SCALAR
                  OBJECT
                  INTERFACE
                  UNION
                  ENUM
                  INPUT_OBJECT
                  LIST
                  NON_NULL
                }
                EOL,
            ],
            [
                $container->introspectionSchema(),
                <<<EOL
                """
                Built-in introspection type.
                """
                type __Schema {
                  description: String
                  types: [__Type!]!
                  queryType: __Type!
                  mutationType: __Type
                  subscriptionType: __Type
                  directives: [__Directive!]!
                }
                EOL,
            ],
            [
                $container->introspectionType(),
                <<<EOL
                """
                Built-in introspection type.
                """
                type __Type {
                  kind: __TypeKind!
                  name: String
                  description: String
                  fields(
                    includeDeprecated: Boolean = false
                  ): [__Field!]
                  interfaces: [__Type!]
                  possibleTypes: [__Type!]
                  enumValues(
                    includeDeprecated: Boolean = false
                  ): [__EnumValue!]
                  inputFields: [__InputValue!]
                  ofType: __Type
                }
                EOL,
            ],
            [
                $container->introspectionDirective(),
                <<<EOL
                """
                Built-in introspection type.
                """
                type __Directive {
                  name: String!
                  description: String
                  locations: [__DirectiveLocation!]!
                  args: [__InputValue!]!
                  isRepeatable: Boolean!
                }
                EOL,
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     */
    public function testSimple(\Graphpinator\Type\Contract\Definition $type, string $print) : void
    {
        self::assertSame($print, $type->printSchema());
    }

    public function testPrintTestSchema() : void
    {
        $expected = <<<EOL
        schema {
          query: Query
          mutation: null
          subscription: null
        }
        
        """
        Test Abc description
        """
        type Abc {
          field1(
            arg1: Int = 123
            arg2: TestInput
          ): TestInterface @deprecated
        }
        
        type Query {
          field0: TestUnion
          fieldInvalidType: TestUnion
          fieldAbstract: TestUnion
          fieldThrow: TestUnion
        }
        
        enum TestEnum {
          A
          B
          C
          D
        }
        
        enum TestExplicitEnum {
          A @deprecated
          B @deprecated
          C @deprecated
          D @deprecated
        }
        
        input TestInnerInput {
          name: String!
          number: [Int!]!
          bool: Boolean
        }
        
        input TestInput {
          name: String!
          inner: TestInnerInput
          innerList: [TestInnerInput!]!
          innerNotNull: TestInnerInput!
        }
        
        interface TestInterface {
          name: String!
        }
        
        union TestUnion = Abc | Xyz
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [TestEnum]
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, TestSchema::getSchema()->printSchema());
    }

    public function testPrintFullTestSchema() : void
    {
        $expected = <<<EOL
        schema {
          query: Query
          mutation: Query
          subscription: Query
        }

        """
        Test Abc description
        """
        type Abc {
          field1(
            arg1: Int = 123
            arg2: TestInput
          ): TestInterface @deprecated
        }
        
        type Query {
          field0: TestUnion
          fieldInvalidType: TestUnion
          fieldAbstract: TestUnion
          fieldThrow: TestUnion
        }

        enum TestEnum {
          A
          B
          C
          D
        }
        
        enum TestExplicitEnum {
          A @deprecated
          B @deprecated
          C @deprecated
          D @deprecated
        }
        
        input TestInnerInput {
          name: String!
          number: [Int!]!
          bool: Boolean
        }
        
        input TestInput {
          name: String!
          inner: TestInnerInput
          innerList: [TestInnerInput!]!
          innerNotNull: TestInnerInput!
        }
        
        interface TestInterface {
          name: String!
        }
        
        union TestUnion = Abc | Xyz
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [TestEnum]
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, TestSchema::getFullSchema()->printSchema());
    }

    public function testPrintTypeKindSorterTestSchema() : void
    {
        $expected = <<<EOL
        schema {
          query: Query
          mutation: null
          subscription: null
        }
        
        interface TestInterface {
          name: String!
        }
        
        """
        Test Abc description
        """
        type Abc {
          field1(
            arg1: Int = 123
            arg2: TestInput
          ): TestInterface @deprecated
        }
        
        type Query {
          field0: TestUnion
          fieldInvalidType: TestUnion
          fieldAbstract: TestUnion
          fieldThrow: TestUnion
        }
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [TestEnum]
        }
        
        union TestUnion = Abc | Xyz
        
        input TestInnerInput {
          name: String!
          number: [Int!]!
          bool: Boolean
        }
        
        input TestInput {
          name: String!
          inner: TestInnerInput
          innerList: [TestInnerInput!]!
          innerNotNull: TestInnerInput!
        }
        
        enum TestEnum {
          A
          B
          C
          D
        }
        
        enum TestExplicitEnum {
          A @deprecated
          B @deprecated
          C @deprecated
          D @deprecated
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, \Graphpinator\Tests\Spec\TestSchema::getSchema()->printSchema(new \Graphpinator\Utils\Sort\TypeKindSorter()));
    }

    public function testPrintTypeKindSorterFullTestSchema() : void
    {
        $expected = <<<EOL
        schema {
          query: Query
          mutation: Query
          subscription: Query
        }
        
        interface TestInterface {
          name: String!
        }
        
        """
        Test Abc description
        """
        type Abc {
          field1(
            arg1: Int = 123
            arg2: TestInput
          ): TestInterface @deprecated
        }
        
        type Query {
          field0: TestUnion
          fieldInvalidType: TestUnion
          fieldAbstract: TestUnion
          fieldThrow: TestUnion
        }
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [TestEnum]
        }
        
        union TestUnion = Abc | Xyz
        
        input TestInnerInput {
          name: String!
          number: [Int!]!
          bool: Boolean
        }
        
        input TestInput {
          name: String!
          inner: TestInnerInput
          innerList: [TestInnerInput!]!
          innerNotNull: TestInnerInput!
        }
        
        enum TestEnum {
          A
          B
          C
          D
        }
        
        enum TestExplicitEnum {
          A @deprecated
          B @deprecated
          C @deprecated
          D @deprecated
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, \Graphpinator\Tests\Spec\TestSchema::getFullSchema()->printSchema(new \Graphpinator\Utils\Sort\TypeKindSorter()));
    }
}
