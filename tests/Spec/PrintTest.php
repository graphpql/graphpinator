<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

// phpcs:disable SlevomatCodingStandard.Files.LineLength.LineTooLong
final class PrintTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        $container = new \Graphpinator\Container\SimpleContainer([], []);

        return [
            [
                \Graphpinator\Container\Container::Int(),
                <<<'EOL'
                """
                Int built-in type
                """
                scalar Int
                EOL,
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
        
        type AddonType {
          dateTime(
            dateTime: DateTime = "2010-01-01 12:12:50"
          ): DateTime
          date(
            date: Date = "2010-01-01"
          ): Date
          emailAddress(
            emailAddress: EmailAddress = "test@test.com"
          ): EmailAddress
          hsla(
            hsla: HslaInput = {
              hue: 180,
              saturation: 50,
              lightness: 50,
              alpha: 0.5
            }
          ): Hsla
          hsl(
            hsl: HslInput = {
              hue: 180,
              saturation: 50,
              lightness: 50
            }
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
            rgba: RgbaInput = {
              red: 150,
              green: 150,
              blue: 150,
              alpha: 0.5
            }
          ): Rgba
          rgb(
            rgb: RgbInput = {
              red: 150,
              green: 150,
              blue: 150
            }
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
          gps(
            gps: GpsInput = {
              lat: 45,
              lng: 90
            }
          ): Gps
          point(
            point: PointInput = {
              x: 420.42,
              y: 420.42
            }
          ): Point
        }
        
        enum ArrayEnum {
          "First description"
          A
        
          "Second description"
          B
        
          "Third description"
          C
        }
        
        input ComplexDefaultsInput {
          innerObject: CompositeInput = {
            name: "testName",
            inner: {
              name: "string",
              number: [
                1,
                2,
                3
              ],
              bool: null
            },
            innerList: [
              {
                name: "string",
                number: [
                  1
                ],
                bool: null
              },
              {
                name: "string",
                number: [
                  1,
                  2,
                  3,
                  4
                ],
                bool: null
              }
            ],
            innerNotNull: {
              name: "string",
              number: [
                1,
                2
              ],
              bool: null
            }
          }
          innerListObjects: [CompositeInput] = [
            {
              name: "testName",
              inner: {
                name: "string",
                number: [
                  1,
                  2,
                  3
                ],
                bool: null
              },
              innerList: [
                {
                  name: "string",
                  number: [
                    1
                  ],
                  bool: null
                },
                {
                  name: "string",
                  number: [
                    1,
                    2,
                    3,
                    4
                  ],
                  bool: null
                }
              ],
              innerNotNull: {
                name: "string",
                number: [
                  1,
                  2
                ],
                bool: null
              }
            },
            {
              name: "testName2",
              inner: {
                name: "string2",
                number: [
                  11,
                  22,
                  33
                ],
                bool: null
              },
              innerList: [
                {
                  name: "string2",
                  number: [
                    11
                  ],
                  bool: null
                },
                {
                  name: "string2",
                  number: [
                    11,
                    22,
                    33,
                    44
                  ],
                  bool: null
                }
              ],
              innerNotNull: {
                name: "string2",
                number: [
                  11,
                  22
                ],
                bool: null
              }
            }
          ]
        }
        
        input CompositeInput {
          name: String!
          inner: SimpleInput
          innerList: [SimpleInput!]!
          innerNotNull: SimpleInput!
        }
        
        input ConstraintInput @objectConstraint(atLeastOne: ["intMinArg", "intMaxArg", "intOneOfArg", "floatMinArg", "floatMaxArg", "floatOneOfArg", "stringMinArg", "stringMaxArg", "stringRegexArg", "stringOneOfArg", "stringOneOfEmptyArg", "listMinArg", "listMaxArg", "listUniqueArg", "listInnerListArg", "listMinIntMinArg"]) {
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
        
        type ConstraintType @objectConstraint(atLeastOne: ["intMinField", "intMaxField", "intOneOfField", "floatMinField", "floatMaxField", "floatOneOfField", "stringMinField", "stringMaxField", "listMinField", "listMaxField"]) {
          intMinField: Int @intConstraint(min: -20)
          intMaxField: Int @intConstraint(max: 20)
          intOneOfField: Int @intConstraint(oneOf: [1, 2, 3])
          floatMinField: Float @floatConstraint(min: 4.01)
          floatMaxField: Float @floatConstraint(max: 20.101)
          floatOneOfField: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringMinField: String @stringConstraint(minLength: 4)
          stringMaxField: String @stringConstraint(maxLength: 10)
          listMinField: [Int] @listConstraint(minItems: 1)
          listMaxField: [Int] @listConstraint(maxItems: 3)
        }
        
        """
        Date type - string which contains valid date in "<YYYY>-<MM>-<DD>" format.
        """
        scalar Date
        
        """
        DateTime type - string which contains valid date in "<YYYY>-<MM>-<DD> <HH>:<MM>:<SS>" format.
        """
        scalar DateTime
        
        input DefaultsInput {
          scalar: String! = "defaultString"
          enum: SimpleEnum! = "A"
          list: [String!]! = [
            "string1",
            "string2"
          ]
          object: SimpleInput! = {
            name: "string",
            number: [
              1,
              2
            ],
            bool: null
          }
          listObjects: [SimpleInput!]! = [
            {
              name: "string",
              number: [
                1
              ],
              bool: null
            },
            {
              name: "string",
              number: [],
              bool: null
            }
          ]
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
        
        """
        EmailAddress type - string which contains valid email address.
        """
        scalar EmailAddress
        
        input ExactlyOneInput @objectConstraint(exactlyOne: ["int1", "int2"]) {
          int1: Int
          int2: Int
        }
        
        """
        Gps type - float longitude and latitude.
        """
        type Gps {
          lat: Float! @floatConstraint(min: -90, max: 90)
          lng: Float! @floatConstraint(min: -180, max: 180)
        }
        
        """
        Hsl type - type representing the HSL color model.
        """
        type Hsl {
          hue: Int!
          saturation: Int!
          lightness: Int!
        }
        
        """
        Hsla type - type representing the HSL color model with added alpha (transparency).
        """
        type Hsla {
          hue: Int!
          saturation: Int!
          lightness: Int!
          alpha: Float!
        }
        
        """
        Ipv4 type - string which contains valid IPv4 address.
        """
        scalar Ipv4
        
        """
        Ipv6 type - string which contains valid IPv6 address.
        """
        scalar Ipv6
        
        """
        Json type - string which contains valid JSON.
        """
        scalar Json
        
        input ListConstraintInput {
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        }
        
        """
        Mac type - string which contains valid MAC (media access control) address.
        """
        scalar Mac
        
        """
        PhoneNumber type - string which contains valid phone number.
        The accepted format is without spaces and other special characters, but the leading plus is required.
        """
        scalar PhoneNumber
        
        """
        Point type - float which contains points x and y.
        """
        type Point {
          x: Float!
          y: Float!
        }
        
        """
        PostalCode type - string which contains valid postal code (ZIP code) in "NNN NN" format.
        """
        scalar PostalCode
        
        type Query {
          fieldUnion: TestUnion
          fieldConstraint(
            arg: ConstraintInput
          ): Int
          fieldExactlyOne(
            arg: ExactlyOneInput
          ): Int
          fieldInvalidType: TestUnion
          fieldThrow: TestUnion
          fieldAddonType: AddonType
          fieldUpload(
            file: Upload
          ): UploadType!
          fieldMultiUpload(
            files: [Upload]
          ): [UploadType!]!
          fieldInputUpload(
            fileInput: UploadInput!
          ): UploadType!
          fieldInputMultiUpload(
            fileInput: UploadInput!
          ): [UploadType!]!
          fieldMultiInputUpload(
            fileInputs: [UploadInput!]!
          ): [UploadType!]!
          fieldMultiInputMultiUpload(
            fileInputs: [UploadInput!]!
          ): [UploadType!]!
        }
        
        """
        Rgb type - type representing the RGB color model.
        """
        type Rgb {
          red: Int!
          green: Int!
          blue: Int!
        }
        
        """
        Rgba type - type representing the RGB color model with added alpha (transparency).
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
        
        """
        TestInterface Description
        """
        interface TestInterface {
          name: String!
        }
        
        scalar TestScalar
        
        union TestUnion = Abc | Xyz
        
        """
        Time type - string which contains time in "<HH>:<MM>:<SS>" format.
        """
        scalar Time
        
        """
        Upload type - represents file which was send to server.
        By GraphQL viewpoint it is scalar type, but it must be used as input only.
        """
        scalar Upload
        
        input UploadInput {
          file: Upload
          files: [Upload]
        }
        
        type UploadType {
          fileName: String
          fileContent: String
        }
        
        """
        Url type - string which contains valid URL (Uniform Resource Locator).
        """
        scalar Url
        
        """
        Void type - accepts null only.
        """
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
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION

        directive @intConstraint(
          min: Int
          max: Int
          oneOf: [Int!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint(
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION

        directive @objectConstraint(
          atLeastOne: [String!]
          exactlyOne: [String!]
        ) on INPUT_OBJECT | INTERFACE | OBJECT
        
        directive @stringConstraint(
          minLength: Int
          maxLength: Int
          regex: String
          oneOf: [String!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION

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
        
        type AddonType {
          dateTime(
            dateTime: DateTime = "2010-01-01 12:12:50"
          ): DateTime
          date(
            date: Date = "2010-01-01"
          ): Date
          emailAddress(
            emailAddress: EmailAddress = "test@test.com"
          ): EmailAddress
          hsla(
            hsla: HslaInput = {
              hue: 180,
              saturation: 50,
              lightness: 50,
              alpha: 0.5
            }
          ): Hsla
          hsl(
            hsl: HslInput = {
              hue: 180,
              saturation: 50,
              lightness: 50
            }
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
            rgba: RgbaInput = {
              red: 150,
              green: 150,
              blue: 150,
              alpha: 0.5
            }
          ): Rgba
          rgb(
            rgb: RgbInput = {
              red: 150,
              green: 150,
              blue: 150
            }
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
          gps(
            gps: GpsInput = {
              lat: 45,
              lng: 90
            }
          ): Gps
          point(
            point: PointInput = {
              x: 420.42,
              y: 420.42
            }
          ): Point
        }
        
        enum ArrayEnum {
          "First description"
          A
        
          "Second description"
          B
        
          "Third description"
          C
        }
        
        input ComplexDefaultsInput {
          innerObject: CompositeInput = {
            name: "testName",
            inner: {
              name: "string",
              number: [
                1,
                2,
                3
              ],
              bool: null
            },
            innerList: [
              {
                name: "string",
                number: [
                  1
                ],
                bool: null
              },
              {
                name: "string",
                number: [
                  1,
                  2,
                  3,
                  4
                ],
                bool: null
              }
            ],
            innerNotNull: {
              name: "string",
              number: [
                1,
                2
              ],
              bool: null
            }
          }
          innerListObjects: [CompositeInput] = [
            {
              name: "testName",
              inner: {
                name: "string",
                number: [
                  1,
                  2,
                  3
                ],
                bool: null
              },
              innerList: [
                {
                  name: "string",
                  number: [
                    1
                  ],
                  bool: null
                },
                {
                  name: "string",
                  number: [
                    1,
                    2,
                    3,
                    4
                  ],
                  bool: null
                }
              ],
              innerNotNull: {
                name: "string",
                number: [
                  1,
                  2
                ],
                bool: null
              }
            },
            {
              name: "testName2",
              inner: {
                name: "string2",
                number: [
                  11,
                  22,
                  33
                ],
                bool: null
              },
              innerList: [
                {
                  name: "string2",
                  number: [
                    11
                  ],
                  bool: null
                },
                {
                  name: "string2",
                  number: [
                    11,
                    22,
                    33,
                    44
                  ],
                  bool: null
                }
              ],
              innerNotNull: {
                name: "string2",
                number: [
                  11,
                  22
                ],
                bool: null
              }
            }
          ]
        }
        
        input CompositeInput {
          name: String!
          inner: SimpleInput
          innerList: [SimpleInput!]!
          innerNotNull: SimpleInput!
        }
        
        input ConstraintInput @objectConstraint(atLeastOne: ["intMinArg", "intMaxArg", "intOneOfArg", "floatMinArg", "floatMaxArg", "floatOneOfArg", "stringMinArg", "stringMaxArg", "stringRegexArg", "stringOneOfArg", "stringOneOfEmptyArg", "listMinArg", "listMaxArg", "listUniqueArg", "listInnerListArg", "listMinIntMinArg"]) {
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
        
        type ConstraintType @objectConstraint(atLeastOne: ["intMinField", "intMaxField", "intOneOfField", "floatMinField", "floatMaxField", "floatOneOfField", "stringMinField", "stringMaxField", "listMinField", "listMaxField"]) {
          intMinField: Int @intConstraint(min: -20)
          intMaxField: Int @intConstraint(max: 20)
          intOneOfField: Int @intConstraint(oneOf: [1, 2, 3])
          floatMinField: Float @floatConstraint(min: 4.01)
          floatMaxField: Float @floatConstraint(max: 20.101)
          floatOneOfField: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringMinField: String @stringConstraint(minLength: 4)
          stringMaxField: String @stringConstraint(maxLength: 10)
          listMinField: [Int] @listConstraint(minItems: 1)
          listMaxField: [Int] @listConstraint(maxItems: 3)
        }
        
        """
        Date type - string which contains valid date in "<YYYY>-<MM>-<DD>" format.
        """
        scalar Date
        
        """
        DateTime type - string which contains valid date in "<YYYY>-<MM>-<DD> <HH>:<MM>:<SS>" format.
        """
        scalar DateTime
        
        input DefaultsInput {
          scalar: String! = "defaultString"
          enum: SimpleEnum! = "A"
          list: [String!]! = [
            "string1",
            "string2"
          ]
          object: SimpleInput! = {
            name: "string",
            number: [
              1,
              2
            ],
            bool: null
          }
          listObjects: [SimpleInput!]! = [
            {
              name: "string",
              number: [
                1
              ],
              bool: null
            },
            {
              name: "string",
              number: [],
              bool: null
            }
          ]
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
        
        """
        EmailAddress type - string which contains valid email address.
        """
        scalar EmailAddress
        
        input ExactlyOneInput @objectConstraint(exactlyOne: ["int1", "int2"]) {
          int1: Int
          int2: Int
        }
        
        """
        Gps type - float longitude and latitude.
        """
        type Gps {
          lat: Float! @floatConstraint(min: -90, max: 90)
          lng: Float! @floatConstraint(min: -180, max: 180)
        }
        
        """
        Hsl type - type representing the HSL color model.
        """
        type Hsl {
          hue: Int!
          saturation: Int!
          lightness: Int!
        }
        
        """
        Hsla type - type representing the HSL color model with added alpha (transparency).
        """
        type Hsla {
          hue: Int!
          saturation: Int!
          lightness: Int!
          alpha: Float!
        }
        
        """
        Ipv4 type - string which contains valid IPv4 address.
        """
        scalar Ipv4
        
        """
        Ipv6 type - string which contains valid IPv6 address.
        """
        scalar Ipv6
        
        """
        Json type - string which contains valid JSON.
        """
        scalar Json
        
        input ListConstraintInput {
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        }
        
        """
        Mac type - string which contains valid MAC (media access control) address.
        """
        scalar Mac
        
        """
        PhoneNumber type - string which contains valid phone number.
        The accepted format is without spaces and other special characters, but the leading plus is required.
        """
        scalar PhoneNumber
        
        """
        Point type - float which contains points x and y.
        """
        type Point {
          x: Float!
          y: Float!
        }
        
        """
        PostalCode type - string which contains valid postal code (ZIP code) in "NNN NN" format.
        """
        scalar PostalCode
        
        type Query {
          fieldUnion: TestUnion
          fieldConstraint(
            arg: ConstraintInput
          ): Int
          fieldExactlyOne(
            arg: ExactlyOneInput
          ): Int
          fieldInvalidType: TestUnion
          fieldThrow: TestUnion
          fieldAddonType: AddonType
          fieldUpload(
            file: Upload
          ): UploadType!
          fieldMultiUpload(
            files: [Upload]
          ): [UploadType!]!
          fieldInputUpload(
            fileInput: UploadInput!
          ): UploadType!
          fieldInputMultiUpload(
            fileInput: UploadInput!
          ): [UploadType!]!
          fieldMultiInputUpload(
            fileInputs: [UploadInput!]!
          ): [UploadType!]!
          fieldMultiInputMultiUpload(
            fileInputs: [UploadInput!]!
          ): [UploadType!]!
        }
        
        """
        Rgb type - type representing the RGB color model.
        """
        type Rgb {
          red: Int!
          green: Int!
          blue: Int!
        }
        
        """
        Rgba type - type representing the RGB color model with added alpha (transparency).
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
        
        """
        TestInterface Description
        """
        interface TestInterface {
          name: String!
        }
        
        scalar TestScalar
        
        union TestUnion = Abc | Xyz
        
        """
        Time type - string which contains time in "<HH>:<MM>:<SS>" format.
        """
        scalar Time
        
        """
        Upload type - represents file which was send to server.
        By GraphQL viewpoint it is scalar type, but it must be used as input only.
        """
        scalar Upload
        
        input UploadInput {
          file: Upload
          files: [Upload]
        }
        
        type UploadType {
          fileName: String
          fileContent: String
        }
        
        """
        Url type - string which contains valid URL (Uniform Resource Locator).
        """
        scalar Url
        
        """
        Void type - accepts null only.
        """
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
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION

        directive @intConstraint(
          min: Int
          max: Int
          oneOf: [Int!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint(
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION
        
        directive @objectConstraint(
          atLeastOne: [String!]
          exactlyOne: [String!]
        ) on INPUT_OBJECT | INTERFACE | OBJECT

        directive @stringConstraint(
          minLength: Int
          maxLength: Int
          regex: String
          oneOf: [String!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION

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
        
        type AddonType {
          dateTime(
            dateTime: DateTime = "2010-01-01 12:12:50"
          ): DateTime
          date(
            date: Date = "2010-01-01"
          ): Date
          emailAddress(
            emailAddress: EmailAddress = "test@test.com"
          ): EmailAddress
          hsla(
            hsla: HslaInput = {
              hue: 180,
              saturation: 50,
              lightness: 50,
              alpha: 0.5
            }
          ): Hsla
          hsl(
            hsl: HslInput = {
              hue: 180,
              saturation: 50,
              lightness: 50
            }
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
            rgba: RgbaInput = {
              red: 150,
              green: 150,
              blue: 150,
              alpha: 0.5
            }
          ): Rgba
          rgb(
            rgb: RgbInput = {
              red: 150,
              green: 150,
              blue: 150
            }
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
          gps(
            gps: GpsInput = {
              lat: 45,
              lng: 90
            }
          ): Gps
          point(
            point: PointInput = {
              x: 420.42,
              y: 420.42
            }
          ): Point
        }
        
        type ConstraintType @objectConstraint(atLeastOne: ["intMinField", "intMaxField", "intOneOfField", "floatMinField", "floatMaxField", "floatOneOfField", "stringMinField", "stringMaxField", "listMinField", "listMaxField"]) {
          intMinField: Int @intConstraint(min: -20)
          intMaxField: Int @intConstraint(max: 20)
          intOneOfField: Int @intConstraint(oneOf: [1, 2, 3])
          floatMinField: Float @floatConstraint(min: 4.01)
          floatMaxField: Float @floatConstraint(max: 20.101)
          floatOneOfField: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringMinField: String @stringConstraint(minLength: 4)
          stringMaxField: String @stringConstraint(maxLength: 10)
          listMinField: [Int] @listConstraint(minItems: 1)
          listMaxField: [Int] @listConstraint(maxItems: 3)
        }
        
        """
        Gps type - float longitude and latitude.
        """
        type Gps {
          lat: Float! @floatConstraint(min: -90, max: 90)
          lng: Float! @floatConstraint(min: -180, max: 180)
        }
        
        """
        Hsl type - type representing the HSL color model.
        """
        type Hsl {
          hue: Int!
          saturation: Int!
          lightness: Int!
        }
        
        """
        Hsla type - type representing the HSL color model with added alpha (transparency).
        """
        type Hsla {
          hue: Int!
          saturation: Int!
          lightness: Int!
          alpha: Float!
        }
        
        """
        Point type - float which contains points x and y.
        """
        type Point {
          x: Float!
          y: Float!
        }
        
        type Query {
          fieldUnion: TestUnion
          fieldConstraint(
            arg: ConstraintInput
          ): Int
          fieldExactlyOne(
            arg: ExactlyOneInput
          ): Int
          fieldInvalidType: TestUnion
          fieldThrow: TestUnion
          fieldAddonType: AddonType
          fieldUpload(
            file: Upload
          ): UploadType!
          fieldMultiUpload(
            files: [Upload]
          ): [UploadType!]!
          fieldInputUpload(
            fileInput: UploadInput!
          ): UploadType!
          fieldInputMultiUpload(
            fileInput: UploadInput!
          ): [UploadType!]!
          fieldMultiInputUpload(
            fileInputs: [UploadInput!]!
          ): [UploadType!]!
          fieldMultiInputMultiUpload(
            fileInputs: [UploadInput!]!
          ): [UploadType!]!
        }
        
        """
        Rgb type - type representing the RGB color model.
        """
        type Rgb {
          red: Int!
          green: Int!
          blue: Int!
        }
        
        """
        Rgba type - type representing the RGB color model with added alpha (transparency).
        """
        type Rgba {
          red: Int!
          green: Int!
          blue: Int!
          alpha: Float!
        }
        
        type UploadType {
          fileName: String
          fileContent: String
        }
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [SimpleEnum]
        }
        
        union TestUnion = Abc | Xyz
        
        input ComplexDefaultsInput {
          innerObject: CompositeInput = {
            name: "testName",
            inner: {
              name: "string",
              number: [
                1,
                2,
                3
              ],
              bool: null
            },
            innerList: [
              {
                name: "string",
                number: [
                  1
                ],
                bool: null
              },
              {
                name: "string",
                number: [
                  1,
                  2,
                  3,
                  4
                ],
                bool: null
              }
            ],
            innerNotNull: {
              name: "string",
              number: [
                1,
                2
              ],
              bool: null
            }
          }
          innerListObjects: [CompositeInput] = [
            {
              name: "testName",
              inner: {
                name: "string",
                number: [
                  1,
                  2,
                  3
                ],
                bool: null
              },
              innerList: [
                {
                  name: "string",
                  number: [
                    1
                  ],
                  bool: null
                },
                {
                  name: "string",
                  number: [
                    1,
                    2,
                    3,
                    4
                  ],
                  bool: null
                }
              ],
              innerNotNull: {
                name: "string",
                number: [
                  1,
                  2
                ],
                bool: null
              }
            },
            {
              name: "testName2",
              inner: {
                name: "string2",
                number: [
                  11,
                  22,
                  33
                ],
                bool: null
              },
              innerList: [
                {
                  name: "string2",
                  number: [
                    11
                  ],
                  bool: null
                },
                {
                  name: "string2",
                  number: [
                    11,
                    22,
                    33,
                    44
                  ],
                  bool: null
                }
              ],
              innerNotNull: {
                name: "string2",
                number: [
                  11,
                  22
                ],
                bool: null
              }
            }
          ]
        }
        
        input CompositeInput {
          name: String!
          inner: SimpleInput
          innerList: [SimpleInput!]!
          innerNotNull: SimpleInput!
        }
        
        input ConstraintInput @objectConstraint(atLeastOne: ["intMinArg", "intMaxArg", "intOneOfArg", "floatMinArg", "floatMaxArg", "floatOneOfArg", "stringMinArg", "stringMaxArg", "stringRegexArg", "stringOneOfArg", "stringOneOfEmptyArg", "listMinArg", "listMaxArg", "listUniqueArg", "listInnerListArg", "listMinIntMinArg"]) {
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
          list: [String!]! = [
            "string1",
            "string2"
          ]
          object: SimpleInput! = {
            name: "string",
            number: [
              1,
              2
            ],
            bool: null
          }
          listObjects: [SimpleInput!]! = [
            {
              name: "string",
              number: [
                1
              ],
              bool: null
            },
            {
              name: "string",
              number: [],
              bool: null
            }
          ]
        }
        
        input ExactlyOneInput @objectConstraint(exactlyOne: ["int1", "int2"]) {
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
        
        input UploadInput {
          file: Upload
          files: [Upload]
        }
        
        """
        Date type - string which contains valid date in "<YYYY>-<MM>-<DD>" format.
        """
        scalar Date
        
        """
        DateTime type - string which contains valid date in "<YYYY>-<MM>-<DD> <HH>:<MM>:<SS>" format.
        """
        scalar DateTime
        
        """
        EmailAddress type - string which contains valid email address.
        """
        scalar EmailAddress
        
        """
        Ipv4 type - string which contains valid IPv4 address.
        """
        scalar Ipv4
        
        """
        Ipv6 type - string which contains valid IPv6 address.
        """
        scalar Ipv6
        
        """
        Json type - string which contains valid JSON.
        """
        scalar Json
        
        """
        Mac type - string which contains valid MAC (media access control) address.
        """
        scalar Mac
        
        """
        PhoneNumber type - string which contains valid phone number.
        The accepted format is without spaces and other special characters, but the leading plus is required.
        """
        scalar PhoneNumber
        
        """
        PostalCode type - string which contains valid postal code (ZIP code) in "NNN NN" format.
        """
        scalar PostalCode
        
        scalar TestScalar
        
        """
        Time type - string which contains time in "<HH>:<MM>:<SS>" format.
        """
        scalar Time
        
        """
        Upload type - represents file which was send to server.
        By GraphQL viewpoint it is scalar type, but it must be used as input only.
        """
        scalar Upload
        
        """
        Url type - string which contains valid URL (Uniform Resource Locator).
        """
        scalar Url
        
        """
        Void type - accepts null only.
        """
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
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION

        directive @intConstraint(
          min: Int
          max: Int
          oneOf: [Int!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint(
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION
        
        directive @objectConstraint(
          atLeastOne: [String!]
          exactlyOne: [String!]
        ) on INPUT_OBJECT | INTERFACE | OBJECT

        directive @stringConstraint(
          minLength: Int
          maxLength: Int
          regex: String
          oneOf: [String!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION

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
        
        type AddonType {
          dateTime(
            dateTime: DateTime = "2010-01-01 12:12:50"
          ): DateTime
          date(
            date: Date = "2010-01-01"
          ): Date
          emailAddress(
            emailAddress: EmailAddress = "test@test.com"
          ): EmailAddress
          hsla(
            hsla: HslaInput = {
              hue: 180,
              saturation: 50,
              lightness: 50,
              alpha: 0.5
            }
          ): Hsla
          hsl(
            hsl: HslInput = {
              hue: 180,
              saturation: 50,
              lightness: 50
            }
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
            rgba: RgbaInput = {
              red: 150,
              green: 150,
              blue: 150,
              alpha: 0.5
            }
          ): Rgba
          rgb(
            rgb: RgbInput = {
              red: 150,
              green: 150,
              blue: 150
            }
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
          gps(
            gps: GpsInput = {
              lat: 45,
              lng: 90
            }
          ): Gps
          point(
            point: PointInput = {
              x: 420.42,
              y: 420.42
            }
          ): Point
        }
        
        type ConstraintType @objectConstraint(atLeastOne: ["intMinField", "intMaxField", "intOneOfField", "floatMinField", "floatMaxField", "floatOneOfField", "stringMinField", "stringMaxField", "listMinField", "listMaxField"]) {
          intMinField: Int @intConstraint(min: -20)
          intMaxField: Int @intConstraint(max: 20)
          intOneOfField: Int @intConstraint(oneOf: [1, 2, 3])
          floatMinField: Float @floatConstraint(min: 4.01)
          floatMaxField: Float @floatConstraint(max: 20.101)
          floatOneOfField: Float @floatConstraint(oneOf: [1.01, 2.02, 3])
          stringMinField: String @stringConstraint(minLength: 4)
          stringMaxField: String @stringConstraint(maxLength: 10)
          listMinField: [Int] @listConstraint(minItems: 1)
          listMaxField: [Int] @listConstraint(maxItems: 3)
        }
        
        """
        Gps type - float longitude and latitude.
        """
        type Gps {
          lat: Float! @floatConstraint(min: -90, max: 90)
          lng: Float! @floatConstraint(min: -180, max: 180)
        }
        
        """
        Hsl type - type representing the HSL color model.
        """
        type Hsl {
          hue: Int!
          saturation: Int!
          lightness: Int!
        }
        
        """
        Hsla type - type representing the HSL color model with added alpha (transparency).
        """
        type Hsla {
          hue: Int!
          saturation: Int!
          lightness: Int!
          alpha: Float!
        }
        
        """
        Point type - float which contains points x and y.
        """
        type Point {
          x: Float!
          y: Float!
        }
        
        type Query {
          fieldUnion: TestUnion
          fieldConstraint(
            arg: ConstraintInput
          ): Int
          fieldExactlyOne(
            arg: ExactlyOneInput
          ): Int
          fieldInvalidType: TestUnion
          fieldThrow: TestUnion
          fieldAddonType: AddonType
          fieldUpload(
            file: Upload
          ): UploadType!
          fieldMultiUpload(
            files: [Upload]
          ): [UploadType!]!
          fieldInputUpload(
            fileInput: UploadInput!
          ): UploadType!
          fieldInputMultiUpload(
            fileInput: UploadInput!
          ): [UploadType!]!
          fieldMultiInputUpload(
            fileInputs: [UploadInput!]!
          ): [UploadType!]!
          fieldMultiInputMultiUpload(
            fileInputs: [UploadInput!]!
          ): [UploadType!]!
        }
        
        """
        Rgb type - type representing the RGB color model.
        """
        type Rgb {
          red: Int!
          green: Int!
          blue: Int!
        }
        
        """
        Rgba type - type representing the RGB color model with added alpha (transparency).
        """
        type Rgba {
          red: Int!
          green: Int!
          blue: Int!
          alpha: Float!
        }
        
        type UploadType {
          fileName: String
          fileContent: String
        }

        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [SimpleEnum]
        }
        
        union TestUnion = Abc | Xyz

        input ComplexDefaultsInput {
          innerObject: CompositeInput = {
            name: "testName",
            inner: {
              name: "string",
              number: [
                1,
                2,
                3
              ],
              bool: null
            },
            innerList: [
              {
                name: "string",
                number: [
                  1
                ],
                bool: null
              },
              {
                name: "string",
                number: [
                  1,
                  2,
                  3,
                  4
                ],
                bool: null
              }
            ],
            innerNotNull: {
              name: "string",
              number: [
                1,
                2
              ],
              bool: null
            }
          }
          innerListObjects: [CompositeInput] = [
            {
              name: "testName",
              inner: {
                name: "string",
                number: [
                  1,
                  2,
                  3
                ],
                bool: null
              },
              innerList: [
                {
                  name: "string",
                  number: [
                    1
                  ],
                  bool: null
                },
                {
                  name: "string",
                  number: [
                    1,
                    2,
                    3,
                    4
                  ],
                  bool: null
                }
              ],
              innerNotNull: {
                name: "string",
                number: [
                  1,
                  2
                ],
                bool: null
              }
            },
            {
              name: "testName2",
              inner: {
                name: "string2",
                number: [
                  11,
                  22,
                  33
                ],
                bool: null
              },
              innerList: [
                {
                  name: "string2",
                  number: [
                    11
                  ],
                  bool: null
                },
                {
                  name: "string2",
                  number: [
                    11,
                    22,
                    33,
                    44
                  ],
                  bool: null
                }
              ],
              innerNotNull: {
                name: "string2",
                number: [
                  11,
                  22
                ],
                bool: null
              }
            }
          ]
        }
        
        input CompositeInput {
          name: String!
          inner: SimpleInput
          innerList: [SimpleInput!]!
          innerNotNull: SimpleInput!
        }
        
        input ConstraintInput @objectConstraint(atLeastOne: ["intMinArg", "intMaxArg", "intOneOfArg", "floatMinArg", "floatMaxArg", "floatOneOfArg", "stringMinArg", "stringMaxArg", "stringRegexArg", "stringOneOfArg", "stringOneOfEmptyArg", "listMinArg", "listMaxArg", "listUniqueArg", "listInnerListArg", "listMinIntMinArg"]) {
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
          list: [String!]! = [
            "string1",
            "string2"
          ]
          object: SimpleInput! = {
            name: "string",
            number: [
              1,
              2
            ],
            bool: null
          }
          listObjects: [SimpleInput!]! = [
            {
              name: "string",
              number: [
                1
              ],
              bool: null
            },
            {
              name: "string",
              number: [],
              bool: null
            }
          ]
        }
        
        input ExactlyOneInput @objectConstraint(exactlyOne: ["int1", "int2"]) {
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
        
        input UploadInput {
          file: Upload
          files: [Upload]
        }
        
        """
        Date type - string which contains valid date in "<YYYY>-<MM>-<DD>" format.
        """
        scalar Date
        
        """
        DateTime type - string which contains valid date in "<YYYY>-<MM>-<DD> <HH>:<MM>:<SS>" format.
        """
        scalar DateTime
        
        """
        EmailAddress type - string which contains valid email address.
        """
        scalar EmailAddress
        
        """
        Ipv4 type - string which contains valid IPv4 address.
        """
        scalar Ipv4
        
        """
        Ipv6 type - string which contains valid IPv6 address.
        """
        scalar Ipv6
        
        """
        Json type - string which contains valid JSON.
        """
        scalar Json
        
        """
        Mac type - string which contains valid MAC (media access control) address.
        """
        scalar Mac
        
        """
        PhoneNumber type - string which contains valid phone number.
        The accepted format is without spaces and other special characters, but the leading plus is required.
        """
        scalar PhoneNumber
        
        """
        PostalCode type - string which contains valid postal code (ZIP code) in "NNN NN" format.
        """
        scalar PostalCode
        
        scalar TestScalar
        
        """
        Time type - string which contains time in "<HH>:<MM>:<SS>" format.
        """
        scalar Time
        
        """
        Upload type - represents file which was send to server.
        By GraphQL viewpoint it is scalar type, but it must be used as input only.
        """
        scalar Upload
        
        """
        Url type - string which contains valid URL (Uniform Resource Locator).
        """
        scalar Url
        
        """
        Void type - accepts null only.
        """
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
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION

        directive @intConstraint(
          min: Int
          max: Int
          oneOf: [Int!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION
        
        directive @invalidDirective repeatable on FIELD
        
        directive @listConstraint(
          minItems: Int
          maxItems: Int
          unique: Boolean = false
          innerList: ListConstraintInput
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION

        directive @objectConstraint(
          atLeastOne: [String!]
          exactlyOne: [String!]
        ) on INPUT_OBJECT | INTERFACE | OBJECT

        directive @stringConstraint(
          minLength: Int
          maxLength: Int
          regex: String
          oneOf: [String!]
        ) on ARGUMENT_DEFINITION | INPUT_FIELD_DEFINITION | FIELD_DEFINITION

        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, TestSchema::getFullSchema()->printSchema(new \Graphpinator\Utils\Sort\TypeKindSorter()));
    }
}
// phpcs:enable SlevomatCodingStandard.Files.LineLength.LineTooLong
