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
        
        scalar Date
        
        scalar DateTime
        
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
        
        scalar EmailAddress
        
        input ExactlyOneInput @inputConstraint(exactlyOne: ["int1", "int2"]) {
          int1: Int
          int2: Int
        }
        
        """
        This add on scalar validates hsl array input with keys and its values -
            hue (0-360), saturation (0-100), lightness (0-100).
            Examples - ["hue" => 180, "saturation\" => 50, "lightness" => 50],
                       ["hue" => 360, "saturation\" => 100, "lightness" => 100],
                       ["hue" => 0, "saturation" => 0, "lightness" => 0]
        """
        type Hsl {
          hue: Int!
          saturation: Int!
          lightness: Int!
        }
        
        """
        This add on scalar validates hsla array input with keys and its values -
            hue (0-360), saturation (0-100), lightness (0-100), alpha (0-1).
            Examples - ["hue" => 180, "saturation\" => 50, "lightness" => 50, "alpha" => 0.5],
                       ["hue" => 360, "saturation\" => 100, "lightness" => 100, "alpha" => 1.0],
                       ["hue" => 0, "saturation" => 0, "lightness" => 0, "alpha" => 0.0]
        """
        type Hsla {
          hue: Int!
          saturation: Int!
          lightness: Int!
          alpha: Float!
        }
        
        scalar Ipv4
        
        scalar Ipv6
        
        scalar Json
        
        input ListConstraintInput {
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        }
        
        scalar Mac
        
        scalar PhoneNumber
        
        scalar PostalCode
        
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
          fieldAddonType: TestAddonDefaultValue
        }
        
        """
        This add on scalar validates rgb array input with keys and its values -
            red (0-255), green (0-255), blue (0-255).
            Examples - ["red" => 100, "green" => 50, "blue" => 50, "alpha" => 0.5],
                       ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
                       ["red" => 0, "green" => 0, "blue" => 0, "alpha" => 0.0]
        """
        type Rgb {
          red: Int!
          green: Int!
          blue: Int!
        }
        
        """
        This add on scalar validates rgba array input with keys and its values -
            red (0-255), green (0-255), blue (0-255), alpha (0-1).
            Examples - ["red" => 100, "green" => 50,  "blue" => 50,  "alpha" => 0.5],
                       ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
                       ["red" => 0,   "green" => 0,   "blue" => 0,   "alpha" => 0.0]
        """
        type Rgba {
          red: Int!
          green: Int!
          blue: Int!
          alpha: Float!
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
        
        type TestAddonDefaultValue {
          dateTime(
            dateTime: DateTime = "01-01-2010 12:12:50"
          ): DateTime
          date(
            date: Date = "01-01-2010"
          ): Date
          emailAddress(
            emailAddress: EmailAddress = "test@test.com"
          ): EmailAddress
          hsla(
            hsla: Hsla = {hue:180,saturation:50,lightness:50,alpha:0.5}
          ): Hsla
          hsl(
            hsl: Hsl = {hue:180,saturation:50,lightness:50}
          ): Hsl
          ipv4(
            ipv4: Ipv4 = "128.0.1.1"
          ): Ipv4
          ipv6(
            ipv6: Ipv6 = "AAAA:1111:FFFF:9999:1111:AAAA:9999:FFFF"
          ): Ipv6
          json(
            json: Json = "{\"testName\":\"testValue\"}"
          ): Json
          mac(
            mac: Mac = "AA:11:FF:99:11:AA"
          ): Mac
          phoneNumber(
            phoneNumber: PhoneNumber = "+999123456789"
          ): PhoneNumber
          postalCode(
            postalCode: PostalCode = "111 22"
          ): PostalCode
          rgba(
            rgba: Rgba = {red:150,green:150,blue:150,alpha:0.5}
          ): Rgba
          rgb(
            rgb: Rgb = {red:150,green:150,blue:150}
          ): Rgb
          time(
            time: Time = "12:12:50"
          ): Time
          url(
            url: Url = "https:\/\/test.com\/boo\/blah.php?testValue=test&testName=name"
          ): Url
          void(
            void: Void = null
          ): Void
        }
        
        """
        TestInterface Description
        """
        interface TestInterface {
          name: String!
        }
        
        scalar TestScalar
        
        union TestUnion = Abc | Xyz
        
        scalar Time
        
        scalar Url
        
        scalar Void
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [SimpleEnum]
        }
        
        directive @floatConstraint(
          min: Float
          max: Float
          oneOf: [Float!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @inputConstraint(
          atLeastOne: [String!]
          exactlyOne: [String!]
        ) on INPUT_OBJECT

        directive @intConstraint(
          min: Int
          max: Int
          oneOf: [Int!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint(
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @stringConstraint(
          minLength: Int
          maxLength: Int
          regex: String
          oneOf: [String!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

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
        
        scalar Date
        
        scalar DateTime
        
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
        
        scalar EmailAddress
        
        input ExactlyOneInput @inputConstraint(exactlyOne: ["int1", "int2"]) {
          int1: Int
          int2: Int
        }
        
        """
        This add on scalar validates hsl array input with keys and its values -
            hue (0-360), saturation (0-100), lightness (0-100).
            Examples - ["hue" => 180, "saturation\" => 50, "lightness" => 50],
                       ["hue" => 360, "saturation\" => 100, "lightness" => 100],
                       ["hue" => 0, "saturation" => 0, "lightness" => 0]
        """
        type Hsl {
          hue: Int!
          saturation: Int!
          lightness: Int!
        }
        
        """
        This add on scalar validates hsla array input with keys and its values -
            hue (0-360), saturation (0-100), lightness (0-100), alpha (0-1).
            Examples - ["hue" => 180, "saturation\" => 50, "lightness" => 50, "alpha" => 0.5],
                       ["hue" => 360, "saturation\" => 100, "lightness" => 100, "alpha" => 1.0],
                       ["hue" => 0, "saturation" => 0, "lightness" => 0, "alpha" => 0.0]
        """
        type Hsla {
          hue: Int!
          saturation: Int!
          lightness: Int!
          alpha: Float!
        }
        
        scalar Ipv4
        
        scalar Ipv6
        
        scalar Json
        
        input ListConstraintInput {
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        }
        
        scalar Mac
        
        scalar PhoneNumber
        
        scalar PostalCode
        
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
          fieldAddonType: TestAddonDefaultValue
        }
        
        """
        This add on scalar validates rgb array input with keys and its values -
            red (0-255), green (0-255), blue (0-255).
            Examples - ["red" => 100, "green" => 50, "blue" => 50, "alpha" => 0.5],
                       ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
                       ["red" => 0, "green" => 0, "blue" => 0, "alpha" => 0.0]
        """
        type Rgb {
          red: Int!
          green: Int!
          blue: Int!
        }
        
        """
        This add on scalar validates rgba array input with keys and its values -
            red (0-255), green (0-255), blue (0-255), alpha (0-1).
            Examples - ["red" => 100, "green" => 50,  "blue" => 50,  "alpha" => 0.5],
                       ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
                       ["red" => 0,   "green" => 0,   "blue" => 0,   "alpha" => 0.0]
        """
        type Rgba {
          red: Int!
          green: Int!
          blue: Int!
          alpha: Float!
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
        
        type TestAddonDefaultValue {
          dateTime(
            dateTime: DateTime = "01-01-2010 12:12:50"
          ): DateTime
          date(
            date: Date = "01-01-2010"
          ): Date
          emailAddress(
            emailAddress: EmailAddress = "test@test.com"
          ): EmailAddress
          hsla(
            hsla: Hsla = {hue:180,saturation:50,lightness:50,alpha:0.5}
          ): Hsla
          hsl(
            hsl: Hsl = {hue:180,saturation:50,lightness:50}
          ): Hsl
          ipv4(
            ipv4: Ipv4 = "128.0.1.1"
          ): Ipv4
          ipv6(
            ipv6: Ipv6 = "AAAA:1111:FFFF:9999:1111:AAAA:9999:FFFF"
          ): Ipv6
          json(
            json: Json = "{\"testName\":\"testValue\"}"
          ): Json
          mac(
            mac: Mac = "AA:11:FF:99:11:AA"
          ): Mac
          phoneNumber(
            phoneNumber: PhoneNumber = "+999123456789"
          ): PhoneNumber
          postalCode(
            postalCode: PostalCode = "111 22"
          ): PostalCode
          rgba(
            rgba: Rgba = {red:150,green:150,blue:150,alpha:0.5}
          ): Rgba
          rgb(
            rgb: Rgb = {red:150,green:150,blue:150}
          ): Rgb
          time(
            time: Time = "12:12:50"
          ): Time
          url(
            url: Url = "https:\/\/test.com\/boo\/blah.php?testValue=test&testName=name"
          ): Url
          void(
            void: Void = null
          ): Void
        }
        
        """
        TestInterface Description
        """
        interface TestInterface {
          name: String!
        }
        
        scalar TestScalar
        
        union TestUnion = Abc | Xyz
        
        scalar Time
        
        scalar Url
        
        scalar Void
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [SimpleEnum]
        }
        
        directive @floatConstraint(
          min: Float
          max: Float
          oneOf: [Float!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @inputConstraint(
          atLeastOne: [String!]
          exactlyOne: [String!]
        ) on INPUT_OBJECT

        directive @intConstraint(
          min: Int
          max: Int
          oneOf: [Int!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint(
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @stringConstraint(
          minLength: Int
          maxLength: Int
          regex: String
          oneOf: [String!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

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
        
        """
        This add on scalar validates hsl array input with keys and its values -
            hue (0-360), saturation (0-100), lightness (0-100).
            Examples - ["hue" => 180, "saturation\" => 50, "lightness" => 50],
                       ["hue" => 360, "saturation\" => 100, "lightness" => 100],
                       ["hue" => 0, "saturation" => 0, "lightness" => 0]
        """
        type Hsl {
          hue: Int!
          saturation: Int!
          lightness: Int!
        }
        
        """
        This add on scalar validates hsla array input with keys and its values -
            hue (0-360), saturation (0-100), lightness (0-100), alpha (0-1).
            Examples - ["hue" => 180, "saturation\" => 50, "lightness" => 50, "alpha" => 0.5],
                       ["hue" => 360, "saturation\" => 100, "lightness" => 100, "alpha" => 1.0],
                       ["hue" => 0, "saturation" => 0, "lightness" => 0, "alpha" => 0.0]
        """
        type Hsla {
          hue: Int!
          saturation: Int!
          lightness: Int!
          alpha: Float!
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
          fieldAddonType: TestAddonDefaultValue
        }
        
        """
        This add on scalar validates rgb array input with keys and its values -
            red (0-255), green (0-255), blue (0-255).
            Examples - ["red" => 100, "green" => 50, "blue" => 50, "alpha" => 0.5],
                       ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
                       ["red" => 0, "green" => 0, "blue" => 0, "alpha" => 0.0]
        """
        type Rgb {
          red: Int!
          green: Int!
          blue: Int!
        }
        
        """
        This add on scalar validates rgba array input with keys and its values -
            red (0-255), green (0-255), blue (0-255), alpha (0-1).
            Examples - ["red" => 100, "green" => 50,  "blue" => 50,  "alpha" => 0.5],
                       ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
                       ["red" => 0,   "green" => 0,   "blue" => 0,   "alpha" => 0.0]
        """
        type Rgba {
          red: Int!
          green: Int!
          blue: Int!
          alpha: Float!
        }
        
        type TestAddonDefaultValue {
          dateTime(
            dateTime: DateTime = "01-01-2010 12:12:50"
          ): DateTime
          date(
            date: Date = "01-01-2010"
          ): Date
          emailAddress(
            emailAddress: EmailAddress = "test@test.com"
          ): EmailAddress
          hsla(
            hsla: Hsla = {hue:180,saturation:50,lightness:50,alpha:0.5}
          ): Hsla
          hsl(
            hsl: Hsl = {hue:180,saturation:50,lightness:50}
          ): Hsl
          ipv4(
            ipv4: Ipv4 = "128.0.1.1"
          ): Ipv4
          ipv6(
            ipv6: Ipv6 = "AAAA:1111:FFFF:9999:1111:AAAA:9999:FFFF"
          ): Ipv6
          json(
            json: Json = "{\"testName\":\"testValue\"}"
          ): Json
          mac(
            mac: Mac = "AA:11:FF:99:11:AA"
          ): Mac
          phoneNumber(
            phoneNumber: PhoneNumber = "+999123456789"
          ): PhoneNumber
          postalCode(
            postalCode: PostalCode = "111 22"
          ): PostalCode
          rgba(
            rgba: Rgba = {red:150,green:150,blue:150,alpha:0.5}
          ): Rgba
          rgb(
            rgb: Rgb = {red:150,green:150,blue:150}
          ): Rgb
          time(
            time: Time = "12:12:50"
          ): Time
          url(
            url: Url = "https:\/\/test.com\/boo\/blah.php?testValue=test&testName=name"
          ): Url
          void(
            void: Void = null
          ): Void
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
        
        input ListConstraintInput {
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        }
        
        input SimpleInput {
          name: String!
          number: [Int!]!
          bool: Boolean
        }
        
        scalar Date
        
        scalar DateTime
        
        scalar EmailAddress
        
        scalar Ipv4
        
        scalar Ipv6
        
        scalar Json
        
        scalar Mac
        
        scalar PhoneNumber
        
        scalar PostalCode
        
        scalar TestScalar
        
        scalar Time
        
        scalar Url
        
        scalar Void
        
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
        
        directive @floatConstraint(
          min: Float
          max: Float
          oneOf: [Float!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @inputConstraint(
          atLeastOne: [String!]
          exactlyOne: [String!]
        ) on INPUT_OBJECT

        directive @intConstraint(
          min: Int
          max: Int
          oneOf: [Int!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint(
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @stringConstraint(
          minLength: Int
          maxLength: Int
          regex: String
          oneOf: [String!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

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
        
        """
        This add on scalar validates hsl array input with keys and its values -
            hue (0-360), saturation (0-100), lightness (0-100).
            Examples - ["hue" => 180, "saturation\" => 50, "lightness" => 50],
                       ["hue" => 360, "saturation\" => 100, "lightness" => 100],
                       ["hue" => 0, "saturation" => 0, "lightness" => 0]
        """
        type Hsl {
          hue: Int!
          saturation: Int!
          lightness: Int!
        }
        
        """
        This add on scalar validates hsla array input with keys and its values -
            hue (0-360), saturation (0-100), lightness (0-100), alpha (0-1).
            Examples - ["hue" => 180, "saturation\" => 50, "lightness" => 50, "alpha" => 0.5],
                       ["hue" => 360, "saturation\" => 100, "lightness" => 100, "alpha" => 1.0],
                       ["hue" => 0, "saturation" => 0, "lightness" => 0, "alpha" => 0.0]
        """
        type Hsla {
          hue: Int!
          saturation: Int!
          lightness: Int!
          alpha: Float!
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
          fieldAddonType: TestAddonDefaultValue
        }
        
        """
        This add on scalar validates rgb array input with keys and its values -
            red (0-255), green (0-255), blue (0-255).
            Examples - ["red" => 100, "green" => 50, "blue" => 50, "alpha" => 0.5],
                       ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
                       ["red" => 0, "green" => 0, "blue" => 0, "alpha" => 0.0]
        """
        type Rgb {
          red: Int!
          green: Int!
          blue: Int!
        }
        
        """
        This add on scalar validates rgba array input with keys and its values -
            red (0-255), green (0-255), blue (0-255), alpha (0-1).
            Examples - ["red" => 100, "green" => 50,  "blue" => 50,  "alpha" => 0.5],
                       ["red" => 255, "green" => 255, "blue" => 255, "alpha" => 1.0],
                       ["red" => 0,   "green" => 0,   "blue" => 0,   "alpha" => 0.0]
        """
        type Rgba {
          red: Int!
          green: Int!
          blue: Int!
          alpha: Float!
        }

        type TestAddonDefaultValue {
          dateTime(
            dateTime: DateTime = "01-01-2010 12:12:50"
          ): DateTime
          date(
            date: Date = "01-01-2010"
          ): Date
          emailAddress(
            emailAddress: EmailAddress = "test@test.com"
          ): EmailAddress
          hsla(
            hsla: Hsla = {hue:180,saturation:50,lightness:50,alpha:0.5}
          ): Hsla
          hsl(
            hsl: Hsl = {hue:180,saturation:50,lightness:50}
          ): Hsl
          ipv4(
            ipv4: Ipv4 = "128.0.1.1"
          ): Ipv4
          ipv6(
            ipv6: Ipv6 = "AAAA:1111:FFFF:9999:1111:AAAA:9999:FFFF"
          ): Ipv6
          json(
            json: Json = "{\"testName\":\"testValue\"}"
          ): Json
          mac(
            mac: Mac = "AA:11:FF:99:11:AA"
          ): Mac
          phoneNumber(
            phoneNumber: PhoneNumber = "+999123456789"
          ): PhoneNumber
          postalCode(
            postalCode: PostalCode = "111 22"
          ): PostalCode
          rgba(
            rgba: Rgba = {red:150,green:150,blue:150,alpha:0.5}
          ): Rgba
          rgb(
            rgb: Rgb = {red:150,green:150,blue:150}
          ): Rgb
          time(
            time: Time = "12:12:50"
          ): Time
          url(
            url: Url = "https:\/\/test.com\/boo\/blah.php?testValue=test&testName=name"
          ): Url
          void(
            void: Void = null
          ): Void
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
        
        input ListConstraintInput {
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        }
        
        input SimpleInput {
          name: String!
          number: [Int!]!
          bool: Boolean
        }
        
        scalar Date
        
        scalar DateTime
        
        scalar EmailAddress
        
        scalar Ipv4
        
        scalar Ipv6
        
        scalar Json
        
        scalar Mac
        
        scalar PhoneNumber
        
        scalar PostalCode
        
        scalar TestScalar
        
        scalar Time
        
        scalar Url
        
        scalar Void
        
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
        
        directive @floatConstraint(
          min: Float
          max: Float
          oneOf: [Float!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @inputConstraint(
          atLeastOne: [String!]
          exactlyOne: [String!]
        ) on INPUT_OBJECT

        directive @intConstraint(
          min: Int
          max: Int
          oneOf: [Int!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint(
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @stringConstraint(
          minLength: Int
          maxLength: Int
          regex: String
          oneOf: [String!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION

        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, TestSchema::getFullSchema()->printSchema(new \Graphpinator\Utils\Sort\TypeKindSorter()));
    }
}
// @phpcs:enable SlevomatCodingStandard.Files.LineLength.LineTooLong
