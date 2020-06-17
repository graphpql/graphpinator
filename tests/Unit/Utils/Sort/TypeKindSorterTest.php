<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Utils\Sort;

final class TypeKindSorterTest extends \PHPUnit\Framework\TestCase
{
    public function testPrintTestSchema() : void
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
        
        type Abc {
          field1(arg1: Int = 123, arg2: TestInput): TestInterface @deprecated
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

    public function testPrintFullTestSchema() : void
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
        
        type Abc {
          field1(arg1: Int = 123, arg2: TestInput): TestInterface @deprecated
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
