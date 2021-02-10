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
                Int built-in type (32 bit)
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
          fieldXyz(
            arg1: Int = 123
            arg2: CompositeInput
          ): Xyz @deprecated
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
        
        input DefaultsInput {
          scalar: String! = "defaultString"
          enum: SimpleEnum! = A
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
        
        type FragmentTypeA implements InterfaceAbc {
          name(
            name: String! = "defaultA"
          ): String!
        }
        
        type FragmentTypeB implements InterfaceEfg {
          name(
            name: String! = "defaultB"
          ): String!
          number(
            number: Int = 5
          ): Int
          bool(
            bool: Boolean = false
          ): Boolean
        }
        
        """
        Interface Abc Description
        """
        interface InterfaceAbc {
          name: String!
        }
        
        type InterfaceChildType implements InterfaceAbc {
          name(
            argName: String = "testValue"
          ): String!
        }
        
        """
        Interface Efg Description
        """
        interface InterfaceEfg implements InterfaceAbc {
          name: String!
          number: Int
        }
        
        type NullFieldResolution {
          stringType(
            nullString: String = null
          ): String!
          interfaceType(
            nullInterface: String = null
          ): TestInterface!
          unionType(
            nullUnion: String = null
          ): TestUnion!
        }
        
        type NullListResolution {
          stringListType(
            nullString: String = null
          ): [String!]!
          interfaceListType(
            nullInterface: [TestInterface] = null
          ): [TestInterface!]!
          unionListType(
            nullUnion: [TestUnion] = null
          ): [TestUnion!]!
        }
        
        type Query {
          fieldAbc: Abc
          fieldUnion: TestUnion
          fieldInvalidType: TestUnionInvalidResolvedType
          fieldThrow: Abc
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
          fieldList: [String!]!
          fieldListList: [[String]]
          fieldListInt: [Int!]!
          fieldListFilter: [FilterData!]!
          fieldListFloat: [Float!]!
          fieldObjectList: [Xyz!]!
          fieldAbstractList: [TestUnion]
          fieldNull: NullFieldResolution
          fieldNullList: NullListResolution
          fieldAbstractNullList: [TestUnion!]!
          fieldArgumentDefaults(
            inputNumberList: [Int]
            inputBool: Boolean
          ): SimpleType!
          fieldInvalidInput: SimpleType
          fieldEmptyObject: SimpleEmptyTestInput
          fieldFragment: InterfaceAbc
          fieldMerge(
            inputComplex: ComplexDefaultsInput!
          ): SimpleType!
          fieldRequiredArgumentInvalid(
            name: String!
          ): SimpleType
          fieldEnumArg(
            val: SimpleEnum!
          ): SimpleEnum!
        }
        
        type SimpleEmptyTestInput {
          fieldNumber: Int
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
        Simple desc
        """
        type SimpleType {
          fieldName(
            name: String! = "testValue"
          ): String!
          fieldNumber(
            number: [Int!]! = [
              1,
              2
            ]
          ): [Int!]!
          fieldBool(
            bool: Boolean = true
          ): Boolean
        }
        
        """
        TestInterface Description
        """
        interface TestInterface {
          name: String!
        }
        
        scalar TestScalar
        
        union TestUnion = Abc | Xyz
        
        union TestUnionInvalidResolvedType = Abc
        
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
        
        type Xyz implements TestInterface {
          name: String!
        }
        
        type Zzz {
          enumList: [SimpleEnum]
        }
        
        directive @invalidDirectiveResult repeatable on FIELD
        
        directive @invalidDirectiveType on FIELD

        directive @testDirective repeatable on FIELD
        EOL;

        self::assertSame($expected, TestSchema::getSchema()->printSchema());
    }

    public function testValidateSchemaProperties() : void
    {
        self::assertTrue(\str_starts_with(
            TestSchema::getFullSchema()->printSchema(),
            'schema {' . \PHP_EOL . '  query: Query' . \PHP_EOL . '  mutation: Query' . \PHP_EOL . '  subscription: Query' . \PHP_EOL . '}',
        ));
    }

    public function testValidateCorrectOrder() : void
    {
        $expected = [
            'interface InterfaceAbc',
            'interface InterfaceEfg',
            'interface TestInterface',
            'type Abc',
            'type FragmentTypeA',
            'type FragmentTypeB',
            'type NullFieldResolution',
            'type NullListResolution',
            'type Query',
            'type SimpleEmptyTestInput',
            'type SimpleType',
            'type UploadType',
            'type Xyz',
            'type Zzz',
            'union TestUnion',
            'union TestUnionInvalidResolvedType',
            'input ComplexDefaultsInput',
            'input CompositeInput',
            'input DefaultsInput',
            'input SimpleInput',
            'input UploadInput',
            'scalar Upload',
            'enum ArrayEnum',
            'enum DescriptionEnum',
            'enum SimpleEnum',
            'directive @invalidDirectiveResult',
            'directive @invalidDirectiveType',
            'directive @testDirective',
        ];

        $schema = TestSchema::getSchema()->printSchema(new \Graphpinator\Utils\Sort\TypeKindSorter());
        $lastCheckedPos = 0;

        foreach ($expected as $type) {
            $pos = \strpos($schema, $type);
            self::assertGreaterThan($lastCheckedPos, $pos);
            $lastCheckedPos = $pos;
        }
    }
}
// phpcs:enable SlevomatCodingStandard.Files.LineLength.LineTooLong
