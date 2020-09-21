<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

// @phpcs:disable SlevomatCodingStandard.Files.LineLength.LineTooLong
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
            arg2: CompositeInput
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
        
        input CompositeInput {
          name: String!
          inner: SimpleInput
          innerList: [SimpleInput!]!
          innerNotNull: SimpleInput!
        }
        
        input ConstraintInput @inputConstraint(atLeastOne: ["intMinArg", "intMaxArg", "intOneOfArg", "floatMinArg", "floatMaxArg", "floatOneOfArg", "stringMinArg", "stringMaxArg", "stringRegexArg", "stringOneOfArg", "stringOneOfEmptyArg", "listMinArg", "listMaxArg", "listUniqueArg", "listInnerListArg", "listMinIntMinArg"]) {
          intMinArg: Int @intConstraint(min: -20)
          intMaxArg: Int @intConstraint(max: 20)
          intOneOfArg: Int @intConstraint(oneOf: [1, 2, 3])
          floatMinArg: Float @floatConstraint(min: 4.01)
          floatMaxArg: Float @floatConstraint(max: 20.101)
          floatOneOfArg: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringMinArg: String @stringConstraint(minLength: 4)
          stringMaxArg: String @stringConstraint(maxLength: 10)
          stringRegexArg: String @stringConstraint(regex: "/^(abc)|(foo)$/")
          stringOneOfArg: String @stringConstraint(oneOf: ["abc", "foo"])
          stringOneOfEmptyArg: String @stringConstraint(oneOf: [])
          listMinArg: [Int] @listConstraint(minItems: 1)
          listMaxArg: [Int] @listConstraint(maxItems: 3)
          listUniqueArg: [Int] @listConstraint(unique: true)
          listInnerListArg: [[Int]] @listConstraint(innerList: {minItems: 1, maxItems: 3})
          listMinIntMinArg: [Int] @listConstraint(minItems: 3) @intConstraint(min: 3)
        }
        
        input DefaultsInput {
          scalar: String! = "defaultString"
          enum: SimpleEnum! = "A"
          list: [String!]! = ["string1","string2"]
          object: SimpleInput! = {name:"string",number:[1,2],bool:null}
          listObjects: [SimpleInput!]! = [{name:"string",number:[1],bool:null},{name:"string",number:[],bool:null}]
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
          D @deprecated(reason: "reason")
        }
        
        input ExactlyOneInput @inputConstraint(exactlyOne: ["int1", "int2"]) {
          int1: Int
          int2: Int
        }
        
        type Query {
          fieldValid: TestUnion
          fieldConstraint(
            arg: ConstraintInput
          ): Int
          fieldExactlyOne(
            arg: ExactlyOneInput
          ): Int
          fieldInvalidType: TestUnion
          fieldInvalidReturn: TestUnion
          fieldThrow: TestUnion
        }
        
        enum SimpleEnum {
          A
          B
          C
          D
        }
        
        input SimpleInput {
          name: String!
          number: [Int!]!
          bool: Boolean
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
        
        directive @floatConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @inputConstraint on INPUT_OBJECT

        directive @intConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @stringConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

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
            arg2: CompositeInput
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
        
        input CompositeInput {
          name: String!
          inner: SimpleInput
          innerList: [SimpleInput!]!
          innerNotNull: SimpleInput!
        }
        
        input ConstraintInput @inputConstraint(atLeastOne: ["intMinArg", "intMaxArg", "intOneOfArg", "floatMinArg", "floatMaxArg", "floatOneOfArg", "stringMinArg", "stringMaxArg", "stringRegexArg", "stringOneOfArg", "stringOneOfEmptyArg", "listMinArg", "listMaxArg", "listUniqueArg", "listInnerListArg", "listMinIntMinArg"]) {
          intMinArg: Int @intConstraint(min: -20)
          intMaxArg: Int @intConstraint(max: 20)
          intOneOfArg: Int @intConstraint(oneOf: [1, 2, 3])
          floatMinArg: Float @floatConstraint(min: 4.01)
          floatMaxArg: Float @floatConstraint(max: 20.101)
          floatOneOfArg: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringMinArg: String @stringConstraint(minLength: 4)
          stringMaxArg: String @stringConstraint(maxLength: 10)
          stringRegexArg: String @stringConstraint(regex: "/^(abc)|(foo)$/")
          stringOneOfArg: String @stringConstraint(oneOf: ["abc", "foo"])
          stringOneOfEmptyArg: String @stringConstraint(oneOf: [])
          listMinArg: [Int] @listConstraint(minItems: 1)
          listMaxArg: [Int] @listConstraint(maxItems: 3)
          listUniqueArg: [Int] @listConstraint(unique: true)
          listInnerListArg: [[Int]] @listConstraint(innerList: {minItems: 1, maxItems: 3})
          listMinIntMinArg: [Int] @listConstraint(minItems: 3) @intConstraint(min: 3)
        }
        
        input DefaultsInput {
          scalar: String! = "defaultString"
          enum: SimpleEnum! = "A"
          list: [String!]! = ["string1","string2"]
          object: SimpleInput! = {name:"string",number:[1,2],bool:null}
          listObjects: [SimpleInput!]! = [{name:"string",number:[1],bool:null},{name:"string",number:[],bool:null}]
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
          D @deprecated(reason: "reason")
        }
        
        input ExactlyOneInput @inputConstraint(exactlyOne: ["int1", "int2"]) {
          int1: Int
          int2: Int
        }
        
        type Query {
          fieldValid: TestUnion
          fieldConstraint(
            arg: ConstraintInput
          ): Int
          fieldExactlyOne(
            arg: ExactlyOneInput
          ): Int
          fieldInvalidType: TestUnion
          fieldInvalidReturn: TestUnion
          fieldThrow: TestUnion
        }
        
        enum SimpleEnum {
          A
          B
          C
          D
        }
        
        input SimpleInput {
          name: String!
          number: [Int!]!
          bool: Boolean
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
        
        directive @floatConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @inputConstraint on INPUT_OBJECT

        directive @intConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @stringConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

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
            arg2: CompositeInput
          ): TestInterface @deprecated
        }
        
        type Query {
          fieldValid: TestUnion
          fieldConstraint(
            arg: ConstraintInput
          ): Int
          fieldExactlyOne(
            arg: ExactlyOneInput
          ): Int
          fieldInvalidType: TestUnion
          fieldInvalidReturn: TestUnion
          fieldThrow: TestUnion
        }
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [SimpleEnum]
        }
        
        union TestUnion = Abc | Xyz
        
        input CompositeInput {
          name: String!
          inner: SimpleInput
          innerList: [SimpleInput!]!
          innerNotNull: SimpleInput!
        }
        
        input ConstraintInput @inputConstraint(atLeastOne: ["intMinArg", "intMaxArg", "intOneOfArg", "floatMinArg", "floatMaxArg", "floatOneOfArg", "stringMinArg", "stringMaxArg", "stringRegexArg", "stringOneOfArg", "stringOneOfEmptyArg", "listMinArg", "listMaxArg", "listUniqueArg", "listInnerListArg", "listMinIntMinArg"]) {
          intMinArg: Int @intConstraint(min: -20)
          intMaxArg: Int @intConstraint(max: 20)
          intOneOfArg: Int @intConstraint(oneOf: [1, 2, 3])
          floatMinArg: Float @floatConstraint(min: 4.01)
          floatMaxArg: Float @floatConstraint(max: 20.101)
          floatOneOfArg: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringMinArg: String @stringConstraint(minLength: 4)
          stringMaxArg: String @stringConstraint(maxLength: 10)
          stringRegexArg: String @stringConstraint(regex: "/^(abc)|(foo)$/")
          stringOneOfArg: String @stringConstraint(oneOf: ["abc", "foo"])
          stringOneOfEmptyArg: String @stringConstraint(oneOf: [])
          listMinArg: [Int] @listConstraint(minItems: 1)
          listMaxArg: [Int] @listConstraint(maxItems: 3)
          listUniqueArg: [Int] @listConstraint(unique: true)
          listInnerListArg: [[Int]] @listConstraint(innerList: {minItems: 1, maxItems: 3})
          listMinIntMinArg: [Int] @listConstraint(minItems: 3) @intConstraint(min: 3)
        }
        
        input DefaultsInput {
          scalar: String! = "defaultString"
          enum: SimpleEnum! = "A"
          list: [String!]! = ["string1","string2"]
          object: SimpleInput! = {name:"string",number:[1,2],bool:null}
          listObjects: [SimpleInput!]! = [{name:"string",number:[1],bool:null},{name:"string",number:[],bool:null}]
        }
        
        input ExactlyOneInput @inputConstraint(exactlyOne: ["int1", "int2"]) {
          int1: Int
          int2: Int
        }
        
        input SimpleInput {
          name: String!
          number: [Int!]!
          bool: Boolean
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
          D @deprecated(reason: "reason")
        }
        
        enum SimpleEnum {
          A
          B
          C
          D
        }
        
        directive @floatConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @inputConstraint on INPUT_OBJECT

        directive @intConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @stringConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

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
            arg2: CompositeInput
          ): TestInterface @deprecated
        }
        
        type Query {
          fieldValid: TestUnion
          fieldConstraint(
            arg: ConstraintInput
          ): Int
          fieldExactlyOne(
            arg: ExactlyOneInput
          ): Int
          fieldInvalidType: TestUnion
          fieldInvalidReturn: TestUnion
          fieldThrow: TestUnion
        }
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [SimpleEnum]
        }
        
        union TestUnion = Abc | Xyz
        
        input CompositeInput {
          name: String!
          inner: SimpleInput
          innerList: [SimpleInput!]!
          innerNotNull: SimpleInput!
        }
        
        input ConstraintInput @inputConstraint(atLeastOne: ["intMinArg", "intMaxArg", "intOneOfArg", "floatMinArg", "floatMaxArg", "floatOneOfArg", "stringMinArg", "stringMaxArg", "stringRegexArg", "stringOneOfArg", "stringOneOfEmptyArg", "listMinArg", "listMaxArg", "listUniqueArg", "listInnerListArg", "listMinIntMinArg"]) {
          intMinArg: Int @intConstraint(min: -20)
          intMaxArg: Int @intConstraint(max: 20)
          intOneOfArg: Int @intConstraint(oneOf: [1, 2, 3])
          floatMinArg: Float @floatConstraint(min: 4.01)
          floatMaxArg: Float @floatConstraint(max: 20.101)
          floatOneOfArg: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringMinArg: String @stringConstraint(minLength: 4)
          stringMaxArg: String @stringConstraint(maxLength: 10)
          stringRegexArg: String @stringConstraint(regex: "/^(abc)|(foo)$/")
          stringOneOfArg: String @stringConstraint(oneOf: ["abc", "foo"])
          stringOneOfEmptyArg: String @stringConstraint(oneOf: [])
          listMinArg: [Int] @listConstraint(minItems: 1)
          listMaxArg: [Int] @listConstraint(maxItems: 3)
          listUniqueArg: [Int] @listConstraint(unique: true)
          listInnerListArg: [[Int]] @listConstraint(innerList: {minItems: 1, maxItems: 3})
          listMinIntMinArg: [Int] @listConstraint(minItems: 3) @intConstraint(min: 3)
        }
        
        input DefaultsInput {
          scalar: String! = "defaultString"
          enum: SimpleEnum! = "A"
          list: [String!]! = ["string1","string2"]
          object: SimpleInput! = {name:"string",number:[1,2],bool:null}
          listObjects: [SimpleInput!]! = [{name:"string",number:[1],bool:null},{name:"string",number:[],bool:null}]
        }
        
        input ExactlyOneInput @inputConstraint(exactlyOne: ["int1", "int2"]) {
          int1: Int
          int2: Int
        }
        
        input SimpleInput {
          name: String!
          number: [Int!]!
          bool: Boolean
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
          D @deprecated(reason: "reason")
        }
        
        enum SimpleEnum {
          A
          B
          C
          D
        }
        
        directive @floatConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @inputConstraint on INPUT_OBJECT

        directive @intConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @stringConstraint on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, TestSchema::getFullSchema()->printSchema(new \Graphpinator\Utils\Sort\TypeKindSorter()));
    }
}
// @phpcs:enable SlevomatCodingStandard.Files.LineLength.LineTooLong
