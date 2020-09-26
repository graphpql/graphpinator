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
        
        input TestAddonType {
          DateTimeType: DateTime = "01-01-2000 04:02:10"
          DateType: Date = "01-01-2000"
          EmailAddressType: EmailAddress = "test@test.com"
          HslaType: HSLA = {"hue":1,"saturation":2,"lightness":3,"alpha":0.5}
          HslType: HSL = {"hue":1,"saturation":2,"lightness":3}
          IPv4Type: IPv4 = "128.0.1.0"
          IPv6Type: IPv6 = "2001:0DB8:85A3:0000:0000:8A2E:0370:7334"
          JsonType: JSON = {"data":{"field0":{"field1":{"name":"Test 123"}}}}
          MacType: MAC = "00-D5-61-A2-AB-13"
          PhoneNumberType: PhoneNumber = "+420123456789"
          PostalCodeType: PostalCode = "111 22"
          RgbaType: RGBA = {"red":1,"green":2,"blue":3,"alpha":0.5}
          RgbType: RGB = {"red":1,"green":2,"blue":3}
          TimeType: Time = "04:02:55"
          UrlType: URL = "www.test.com"
          VoidType: Void
        }
        
        input TestDefaultValue1 {
          stringArgument1: String = null
          stringArgument2: String = "testValue"
          intArgument1: Int = null
          intArgument2: Int = 6247
          inputArgument1: TestDefaultValue2 = {
            notNullListIntArgument1: [66, 55],
            listIntArgument1: [66, null],
            notNullIntArgument1: 420,
            notNullListStringArgument1: ["Boo", "Baz"],
            listStringArgument1: ["Boo", null],
            notNullStringArgument1: "notNullValue"
          }
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
        
        input TestAddonType {
          DateTimeType: DateTime = "01-01-2000 04:02:10"
          DateType: Date = "01-01-2000"
          EmailAddressType: EmailAddress = "test@test.com"
          HslaType: HSLA = {"hue":1,"saturation":2,"lightness":3,"alpha":0.5}
          HslType: HSL = {"hue":1,"saturation":2,"lightness":3}
          IPv4Type: IPv4 = "128.0.1.0"
          IPv6Type: IPv6 = "2001:0DB8:85A3:0000:0000:8A2E:0370:7334"
          JsonType: JSON = {"data":{"field0":{"field1":{"name":"Test 123"}}}}
          MacType: MAC = "00-D5-61-A2-AB-13"
          PhoneNumberType: PhoneNumber = "+420123456789"
          PostalCodeType: PostalCode = "111 22"
          RgbaType: RGBA = {"red":1,"green":2,"blue":3,"alpha":0.5}
          RgbType: RGB = {"red":1,"green":2,"blue":3}
          TimeType: Time = "04:02:55"
          UrlType: URL = "www.test.com"
          VoidType: Void
        }
        
        input TestDefaultValue1 {
          stringArgument1: String = null
          stringArgument2: String = "testValue"
          intArgument1: Int = null
          intArgument2: Int = 6247
          inputArgument1: TestDefaultValue2 = {
            notNullListIntArgument1: [66, 55],
            listIntArgument1: [66, null],
            notNullIntArgument1: 420,
            notNullListStringArgument1: ["Boo", "Baz"],
            listStringArgument1: ["Boo", null],
            notNullStringArgument1: "notNullValue"
          }
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
        
        input TestAddonType {
          DateTimeType: DateTime = "01-01-2000 04:02:10"
          DateType: Date = "01-01-2000"
          EmailAddressType: EmailAddress = "test@test.com"
          HslaType: HSLA = {"hue":1,"saturation":2,"lightness":3,"alpha":0.5}
          HslType: HSL = {"hue":1,"saturation":2,"lightness":3}
          IPv4Type: IPv4 = "128.0.1.0"
          IPv6Type: IPv6 = "2001:0DB8:85A3:0000:0000:8A2E:0370:7334"
          JsonType: JSON = {"data":{"field0":{"field1":{"name":"Test 123"}}}}
          MacType: MAC = "00-D5-61-A2-AB-13"
          PhoneNumberType: PhoneNumber = "+420123456789"
          PostalCodeType: PostalCode = "111 22"
          RgbaType: RGBA = {"red":1,"green":2,"blue":3,"alpha":0.5}
          RgbType: RGB = {"red":1,"green":2,"blue":3}
          TimeType: Time = "04:02:55"
          UrlType: URL = "www.test.com"
          VoidType: Void
        }
        
        input TestDefaultValue1 {
          stringArgument1: String = null
          stringArgument2: String = "testValue"
          intArgument1: Int = null
          intArgument2: Int = 6247
          inputArgument1: TestDefaultValue2 = {
            notNullListIntArgument1: [66, 55],
            listIntArgument1: [66, null],
            notNullIntArgument1: 420,
            notNullListStringArgument1: ["Boo", "Baz"],
            listStringArgument1: ["Boo", null],
            notNullStringArgument1: "notNullValue"
          }
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
        
        input TestAddonType {
          DateTimeType: DateTime = "01-01-2000 04:02:10"
          DateType: Date = "01-01-2000"
          EmailAddressType: EmailAddress = "test@test.com"
          HslaType: HSLA = {"hue":1,"saturation":2,"lightness":3,"alpha":0.5}
          HslType: HSL = {"hue":1,"saturation":2,"lightness":3}
          IPv4Type: IPv4 = "128.0.1.0"
          IPv6Type: IPv6 = "2001:0DB8:85A3:0000:0000:8A2E:0370:7334"
          JsonType: JSON = {"data":{"field0":{"field1":{"name":"Test 123"}}}}
          MacType: MAC = "00-D5-61-A2-AB-13"
          PhoneNumberType: PhoneNumber = "+420123456789"
          PostalCodeType: PostalCode = "111 22"
          RgbaType: RGBA = {"red":1,"green":2,"blue":3,"alpha":0.5}
          RgbType: RGB = {"red":1,"green":2,"blue":3}
          TimeType: Time = "04:02:55"
          UrlType: URL = "www.test.com"
          VoidType: Void
        }
        
        input TestDefaultValue1 {
          stringArgument1: String = null
          stringArgument2: String = "testValue"
          intArgument1: Int = null
          intArgument2: Int = 6247
          inputArgument1: TestDefaultValue2 = {
            notNullListIntArgument1: [66, 55],
            listIntArgument1: [66, null],
            notNullIntArgument1: 420,
            notNullListStringArgument1: ["Boo", "Baz"],
            listStringArgument1: ["Boo", null],
            notNullStringArgument1: "notNullValue"
          }
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
