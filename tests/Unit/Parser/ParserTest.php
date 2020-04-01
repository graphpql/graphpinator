<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Parser;

final class ParserTest extends \PHPUnit\Framework\TestCase
{
    public function testQuery() : void
    {
        $parser = new \Graphpinator\Parser\Parser('query queryName {}');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function testQueryNoName() : void
    {
        $parser = new \Graphpinator\Parser\Parser('query {}');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function testQueryShorthand() : void
    {
        $parser = new \Graphpinator\Parser\Parser('{}');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function testQueryMultiple() : void
    {
        $parser = new \Graphpinator\Parser\Parser('query {} mutation {}');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function testFragment() : void
    {
        $parser = new \Graphpinator\Parser\Parser('fragment fragmentName on TypeName {} query queryName {}');
        $result = $parser->parse();

        self::assertCount(1, $result->getFragments());
    }

    public function testNamedFragmentSpread() : void
    {
        $parser = new \Graphpinator\Parser\Parser('query { ... fragmentName } ');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function testTypeFragmentSpread() : void
    {
        $parser = new \Graphpinator\Parser\Parser('query { ... on Type {} }');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function testVariable() : void
    {
        $parser = new \Graphpinator\Parser\Parser('query queryName ($varName: Int) {}');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function testVariableDefault() : void
    {
        $parser = new \Graphpinator\Parser\Parser('query queryName ($varName: Int = 3) {}');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function testVariableComplexType() : void
    {
        $parser = new \Graphpinator\Parser\Parser('query queryName ($varName: [Int!]!) {}');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function testVariableMultiple() : void
    {
        $parser = new \Graphpinator\Parser\Parser('query queryName ($varName: Boolean = true, $varName2: Boolean!) {}');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function testVariableDefaultList() : void
    {
        $parser = new \Graphpinator\Parser\Parser('query queryName ($varName: [Bool] = [true, false]) {}');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function testVariableDefaultObject() : void
    {
        $parser = new \Graphpinator\Parser\Parser('query queryName ($varName: InputType = {fieldName: null, fieldName2: {}}) {}');
        $result = $parser->parse();

        self::assertCount(0, $result->getFragments());
    }

    public function invalidDataProvider() : array
    {
        return [
            // empty
            [''],
            // no operation
            ['fragment fragmentName on TypeName {}'],
            // missing operation type
            ['queryName {}'],
            // missing operation name
            ['query ($var: Int) {}'],
            // invalid variable syntax
            ['query queryName [$var: Int] {}'],
            // invalid fragment spread
            ['query queryName { ... {} }'],
            ['query queryName { ... on {} }'],
            // invalid variable value
            ['query queryName ($var: Int = $var2) {}'],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalid(string $input) : void
    {
        $this->expectException(\Exception::class);

        $parser = new \Graphpinator\Parser\Parser($input);
        $parser->parse();
    }
}
