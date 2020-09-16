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
                    includeDeprecated: Boolean! = false
                  ): [__Field!]
                  interfaces: [__Type!]
                  possibleTypes: [__Type!]
                  enumValues(
                    includeDeprecated: Boolean! = false
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
        
        enum ArrayEnum {
          "First description"
          A
        
          "Second description"
          B
        
          "Third description"
          C
        }
        
        input ConstraintInput {
          intArg: Int @intConstraint(max: 40)
          intNotNullArg: Int! @intConstraint(min: -20)
          intOneOfArg: Int @intConstraint(oneOf: [1, 2, 3])
          floatArg: Float @floatConstraint(max: 4.01)
          floatNotNullArg: Float! @floatConstraint(min: -20.101)
          floatOneOfArg: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringArg: String @stringConstraint(maxLength: 4)
          stringNotNullArg: String! @stringConstraint(minLength: 4)
          stringRegexArg: String @stringConstraint(regex: "(abc)|(foo)")
          stringOneOfArg: String @stringConstraint(oneOf: ["abc", "foo"])
          stringOneOfEmptyArg: String @stringConstraint(oneOf: [])
        }
        
        enum DescriptionEnum {
          "single line description"
          A
        
          B @deprecated
        
          """
          multi line
          description
          """
          C
        
          "single line description"
          D @deprecated
        }
        
        type Query {
          field0: TestUnion
          fieldInvalidType: TestUnion
          fieldAbstract: TestUnion
          fieldThrow: TestUnion
        }
        
        enum SimpleEnum {
          A
          B
          C
          D
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
        
        """
        TestInterface Description
        """
        interface TestInterface {
          name: String!
        }
        
        scalar TestScalar
        
        union TestUnion = Abc | Xyz
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [SimpleEnum]
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, TestSchema::getSchema()->printSchema());
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
        
        enum ArrayEnum {
          "First description"
          A
        
          "Second description"
          B
        
          "Third description"
          C
        }
        
        input ConstraintInput {
          intArg: Int @intConstraint(max: 40)
          intNotNullArg: Int! @intConstraint(min: -20)
          intOneOfArg: Int @intConstraint(oneOf: [1, 2, 3])
          floatArg: Float @floatConstraint(max: 4.01)
          floatNotNullArg: Float! @floatConstraint(min: -20.101)
          floatOneOfArg: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringArg: String @stringConstraint(maxLength: 4)
          stringNotNullArg: String! @stringConstraint(minLength: 4)
          stringRegexArg: String @stringConstraint(regex: "(abc)|(foo)")
          stringOneOfArg: String @stringConstraint(oneOf: ["abc", "foo"])
          stringOneOfEmptyArg: String @stringConstraint(oneOf: [])
        }
        
        enum DescriptionEnum {
          "single line description"
          A
        
          B @deprecated
        
          """
          multi line
          description
          """
          C
        
          "single line description"
          D @deprecated
        }
        
        type Query {
          field0: TestUnion
          fieldInvalidType: TestUnion
          fieldAbstract: TestUnion
          fieldThrow: TestUnion
        }
        
        enum SimpleEnum {
          A
          B
          C
          D
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
        
        """
        TestInterface Description
        """
        interface TestInterface {
          name: String!
        }
        
        scalar TestScalar
        
        union TestUnion = Abc | Xyz
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [SimpleEnum]
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, TestSchema::getFullSchema()->printSchema());
    }

    public function testPrintTypeKindSorterSchema() : void
    {
        $expected = <<<'EOL'
        schema {
          query: Query
          mutation: null
          subscription: null
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
          enumList: [SimpleEnum]
        }
        
        union TestUnion = Abc | Xyz
        
        input ConstraintInput {
          intArg: Int @intConstraint(max: 40)
          intNotNullArg: Int! @intConstraint(min: -20)
          intOneOfArg: Int @intConstraint(oneOf: [1, 2, 3])
          floatArg: Float @floatConstraint(max: 4.01)
          floatNotNullArg: Float! @floatConstraint(min: -20.101)
          floatOneOfArg: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringArg: String @stringConstraint(maxLength: 4)
          stringNotNullArg: String! @stringConstraint(minLength: 4)
          stringRegexArg: String @stringConstraint(regex: "(abc)|(foo)")
          stringOneOfArg: String @stringConstraint(oneOf: ["abc", "foo"])
          stringOneOfEmptyArg: String @stringConstraint(oneOf: [])
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
        
        scalar TestScalar
        
        enum ArrayEnum {
          "First description"
          A
        
          "Second description"
          B
        
          "Third description"
          C
        }
        
        enum DescriptionEnum {
          "single line description"
          A
        
          B @deprecated
        
          """
          multi line
          description
          """
          C
        
          "single line description"
          D @deprecated
        }
        
        enum SimpleEnum {
          A
          B
          C
          D
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, TestSchema::getSchema()->printSchema(new \Graphpinator\Utils\Sort\TypeKindSorter()));
    }

    public function testPrintTypeKindSorterFullSchema() : void
    {
        $expected = <<<'EOL'
        schema {
          query: Query
          mutation: Query
          subscription: Query
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
          enumList: [SimpleEnum]
        }
        
        union TestUnion = Abc | Xyz
        
        input ConstraintInput {
          intArg: Int @intConstraint(max: 40)
          intNotNullArg: Int! @intConstraint(min: -20)
          intOneOfArg: Int @intConstraint(oneOf: [1, 2, 3])
          floatArg: Float @floatConstraint(max: 4.01)
          floatNotNullArg: Float! @floatConstraint(min: -20.101)
          floatOneOfArg: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringArg: String @stringConstraint(maxLength: 4)
          stringNotNullArg: String! @stringConstraint(minLength: 4)
          stringRegexArg: String @stringConstraint(regex: "(abc)|(foo)")
          stringOneOfArg: String @stringConstraint(oneOf: ["abc", "foo"])
          stringOneOfEmptyArg: String @stringConstraint(oneOf: [])
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
        
        scalar TestScalar
        
        enum ArrayEnum {
          "First description"
          A
        
          "Second description"
          B
        
          "Third description"
          C
        }
        
        enum DescriptionEnum {
          "single line description"
          A
        
          B @deprecated
        
          """
          multi line
          description
          """
          C
        
          "single line description"
          D @deprecated
        }
        
        enum SimpleEnum {
          A
          B
          C
          D
        }
        
        directive @invalidDirective repeatable on FIELD
        
        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, TestSchema::getFullSchema()->printSchema(new \Graphpinator\Utils\Sort\TypeKindSorter()));
    }
}
