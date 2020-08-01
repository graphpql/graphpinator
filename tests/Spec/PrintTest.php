<?php

declare(strict_types = 1);

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
                <<<'EOL'
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
                <<<'EOL'
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
                <<<'EOL'
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
                <<<'EOL'
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
     * @param \Graphpinator\Type\Contract\Definition $type
     * @param string $print
     */
    public function testSimple(\Graphpinator\Type\Contract\Definition $type, string $print) : void
    {
        self::assertSame($print, $type->printSchema());
    }

    public function testPrintSchema() : void
    {
        $expected = <<<'EOL'
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
        
        """
        ETestEnum description
        """
        enum ETestEnum {
          "single line description"
          A
        
          "single line description"
          B
        
          "single line description"
          C
        
          "single line description"
          D
        }
        
        interface ITestInterface {
          name: String!
        }
        
        """
        ITestInterface2 Description
        """
        interface ITestInterface2 {
          "single line description"
          name: String!
        }
        
        type Query {
          field0: TestUnion
          fieldInvalidType: TestUnion
          fieldAbstract: TestUnion
          fieldThrow: TestUnion
        }
        
        enum TestEnum {
          A
        
          "single line description"
          B
        
          C
        
          """
          multi line
          description
          """
          D
        }
        
        enum TestExplicitEnum {
          "single line description"
          A
        
          B @deprecated
        
          """
          multi line
          description
          """
          C
        
          "single line description 2"
          D @deprecated
        }
        
        input TestInnerInput {
          name: String!
        
          "single line description"
          number: [Int!]!
        
          """
          multi line
          description
          """
          bool: Boolean
        }
        
        input TestInput {
          name: String!
        
          """
          multi line
          description
          """
          inner: TestInnerInput
        
          """
          multi line
          description
          """
          innerList: [TestInnerInput!]!
        
          "single line description"
          innerNotNull: TestInnerInput!
        }
        
        """
        TestInterface Description
        """
        interface TestInterface {
          name: String!
        }
        
        scalar TestScalar

        scalar TestSecondScalar
        
        union TestUnion = Abc | Xyz
        
        union UTestUnion = Abc | Xyz
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [TestEnum]
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, PrintSchema::getSchema()->printSchema());
    }

    public function testPrintFullSchema() : void
    {
        $expected = <<<'EOL'
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
        
        """
        ETestEnum description
        """
        enum ETestEnum {
          "single line description"
          A
        
          "single line description"
          B
        
          "single line description"
          C
        
          "single line description"
          D
        }
        
        interface ITestInterface {
          name: String!
        }
        
        """
        ITestInterface2 Description
        """
        interface ITestInterface2 {
          "single line description"
          name: String!
        }
        
        type Query {
          field0: TestUnion
          fieldInvalidType: TestUnion
          fieldAbstract: TestUnion
          fieldThrow: TestUnion
        }

        enum TestEnum {
          A
        
          "single line description"
          B
        
          C
        
          """
          multi line
          description
          """
          D
        }
        
        enum TestExplicitEnum {
          "single line description"
          A
        
          B @deprecated
        
          """
          multi line
          description
          """
          C
        
          "single line description 2"
          D @deprecated
        }
        
        input TestInnerInput {
          name: String!
        
          "single line description"
          number: [Int!]!
        
          """
          multi line
          description
          """
          bool: Boolean
        }
        
        input TestInput {
          name: String!
        
          """
          multi line
          description
          """
          inner: TestInnerInput
        
          """
          multi line
          description
          """
          innerList: [TestInnerInput!]!
        
          "single line description"
          innerNotNull: TestInnerInput!
        }
        
        """
        TestInterface Description
        """
        interface TestInterface {
          name: String!
        }
        
        scalar TestScalar

        scalar TestSecondScalar
        
        union TestUnion = Abc | Xyz
        
        union UTestUnion = Abc | Xyz
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [TestEnum]
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, PrintSchema::getFullSchema()->printSchema());
    }

    public function testPrintTypeKindSorterSchema() : void
    {
        $expected = <<<'EOL'
        schema {
          query: Query
          mutation: null
          subscription: null
        }
        
        interface ITestInterface {
          name: String!
        }
        
        """
        ITestInterface2 Description
        """
        interface ITestInterface2 {
          "single line description"
          name: String!
        }
        
        """
        TestInterface Description
        """
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
        
        union UTestUnion = Abc | Xyz
        
        input TestInnerInput {
          name: String!
        
          "single line description"
          number: [Int!]!
        
          """
          multi line
          description
          """
          bool: Boolean
        }
        
        input TestInput {
          name: String!
        
          """
          multi line
          description
          """
          inner: TestInnerInput
        
          """
          multi line
          description
          """
          innerList: [TestInnerInput!]!
        
          "single line description"
          innerNotNull: TestInnerInput!
        }
        
        scalar TestScalar

        scalar TestSecondScalar
        
        """
        ETestEnum description
        """
        enum ETestEnum {
          "single line description"
          A
        
          "single line description"
          B
        
          "single line description"
          C
        
          "single line description"
          D
        }
        
        enum TestEnum {
          A
        
          "single line description"
          B
        
          C
        
          """
          multi line
          description
          """
          D
        }
        
        enum TestExplicitEnum {
          "single line description"
          A
        
          B @deprecated
        
          """
          multi line
          description
          """
          C
        
          "single line description 2"
          D @deprecated
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, PrintSchema::getSchema()->printSchema(new \Graphpinator\Utils\Sort\TypeKindSorter()));
    }

    public function testPrintTypeKindSorterFullSchema() : void
    {
        $expected = <<<'EOL'
        schema {
          query: Query
          mutation: Query
          subscription: Query
        }
        
        interface ITestInterface {
          name: String!
        }
        
        """
        ITestInterface2 Description
        """
        interface ITestInterface2 {
          "single line description"
          name: String!
        }
        
        """
        TestInterface Description
        """
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
        
        union UTestUnion = Abc | Xyz
        
        input TestInnerInput {
          name: String!
        
          "single line description"
          number: [Int!]!
        
          """
          multi line
          description
          """
          bool: Boolean
        }
        
        input TestInput {
          name: String!
        
          """
          multi line
          description
          """
          inner: TestInnerInput
        
          """
          multi line
          description
          """
          innerList: [TestInnerInput!]!
        
          "single line description"
          innerNotNull: TestInnerInput!
        }
        
        scalar TestScalar

        scalar TestSecondScalar
        
        """
        ETestEnum description
        """
        enum ETestEnum {
          "single line description"
          A
        
          "single line description"
          B
        
          "single line description"
          C
        
          "single line description"
          D
        }
        
        enum TestEnum {
          A
        
          "single line description"
          B
        
          C
        
          """
          multi line
          description
          """
          D
        }
        
        enum TestExplicitEnum {
          "single line description"
          A
        
          B @deprecated
        
          """
          multi line
          description
          """
          C
        
          "single line description 2"
          D @deprecated
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, PrintSchema::getFullSchema()->printSchema(new \Graphpinator\Utils\Sort\TypeKindSorter()));
    }
}
