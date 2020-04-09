<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Normalizer;

final class NormalizerTest extends \PHPUnit\Framework\TestCase
{
    public function testFragmentSpread() : void
    {
        $parseResult = new \Graphpinator\Parser\ParseResult(
            new \Graphpinator\Parser\Operation(
                new \Graphpinator\Parser\FieldSet(
                    [new \Graphpinator\Parser\Field('operationField', null, null, null)],
                    new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([
                        new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragmentName'),
                    ]),
                ),
            ),
            new \Graphpinator\Parser\Fragment\FragmentSet([
                new \Graphpinator\Parser\Fragment\Fragment(
                    'fragmentName',
                    new \Graphpinator\Parser\TypeRef\NamedTypeRef('Abc'),
                    new \Graphpinator\Parser\FieldSet(
                        [new \Graphpinator\Parser\Field('fragmentField', null, null, null)],
                        new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([]),
                    ),
                ),
            ]),
        );

        $normalizer = new \Graphpinator\Normalizer\Normalizer(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $operation = $normalizer->normalize($parseResult);

        self::assertCount(0, $operation->getVariables());
        self::assertCount(2, $operation->getChildren());
        self::assertArrayHasKey('operationField', $operation->getChildren());
        self::assertSame('operationField', $operation->getChildren()->offsetGet('operationField')->getName());
        self::assertSame('operationField', $operation->getChildren()->offsetGet('operationField')->getAlias());
        self::assertCount(0, $operation->getChildren()->offsetGet('operationField')->getArguments());
        self::assertNull($operation->getChildren()->offsetGet('operationField')->getFields());
        self::assertNull($operation->getChildren()->offsetGet('operationField')->getTypeCondition());
        self::assertArrayHasKey('fragmentField', $operation->getChildren());
        self::assertSame('fragmentField', $operation->getChildren()->offsetGet('fragmentField')->getName());
        self::assertSame('fragmentField', $operation->getChildren()->offsetGet('fragmentField')->getAlias());
        self::assertCount(0, $operation->getChildren()->offsetGet('fragmentField')->getArguments());
        self::assertNull($operation->getChildren()->offsetGet('fragmentField')->getFields());
        self::assertSame('Abc', $operation->getChildren()->offsetGet('fragmentField')->getTypeCondition()->getName());
    }

    public function testVariableTypeReferences() : void
    {
        $parseResult = new \Graphpinator\Parser\ParseResult(
            new \Graphpinator\Parser\Operation(
                new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([])),
                \Graphpinator\Tokenizer\OperationType::QUERY,
                'operationName',
                new \Graphpinator\Parser\Variable\VariableSet([
                    new \Graphpinator\Parser\Variable\Variable(
                        'varName',
                        new \Graphpinator\Parser\TypeRef\NotNullRef(new \Graphpinator\Parser\TypeRef\NamedTypeRef('Abc')),
                    ),
                    new \Graphpinator\Parser\Variable\Variable(
                        'varNameList',
                        new \Graphpinator\Parser\TypeRef\ListTypeRef(new \Graphpinator\Parser\TypeRef\NamedTypeRef('Abc')),
                    )
                ])
            ),
            new \Graphpinator\Parser\Fragment\FragmentSet([]),
        );

        $normalizer = new \Graphpinator\Normalizer\Normalizer(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $operation = $normalizer->normalize($parseResult);

        self::assertCount(0, $operation->getChildren());
        self::assertCount(2, $operation->getVariables());
        self::assertArrayHasKey('varName', $operation->getVariables());
        self::assertSame('varName', $operation->getVariables()->offsetGet('varName')->getName());
        self::assertNull($operation->getVariables()->offsetGet('varName')->getDefault());
        self::assertInstanceOf(\Graphpinator\Type\NotNullType::class, $operation->getVariables()->offsetGet('varName')->getType());
        self::assertSame('Abc', $operation->getVariables()->offsetGet('varName')->getType()->getNamedType()->getName());
        self::assertArrayHasKey('varNameList', $operation->getVariables());
        self::assertSame('varNameList', $operation->getVariables()->offsetGet('varNameList')->getName());
        self::assertNull($operation->getVariables()->offsetGet('varNameList')->getDefault());
        self::assertInstanceOf(\Graphpinator\Type\ListType::class, $operation->getVariables()->offsetGet('varNameList')->getType());
        self::assertSame('Abc', $operation->getVariables()->offsetGet('varNameList')->getType()->getNamedType()->getName());
    }

    public function testVariableValues() : void
    {
        self::assertTrue(true);
    }

    public function invalidDataProvider() : array
    {
        return [
            [
                new \Graphpinator\Parser\ParseResult(
                    new \Graphpinator\Parser\Operation(
                        new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([])),
                        \Graphpinator\Tokenizer\OperationType::MUTATION,
                    ),
                    new \Graphpinator\Parser\Fragment\FragmentSet([]),
                ),
            ],
            [
                new \Graphpinator\Parser\ParseResult(
                    new \Graphpinator\Parser\Operation(
                        new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([])),
                        \Graphpinator\Tokenizer\OperationType::SUBSCRIPTION,
                    ),
                    new \Graphpinator\Parser\Fragment\FragmentSet([]),
                ),
            ],
            [
                new \Graphpinator\Parser\ParseResult(
                    new \Graphpinator\Parser\Operation(
                        new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([])),
                        'random',
                    ),
                    new \Graphpinator\Parser\Fragment\FragmentSet([]),
                ),
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalid(\Graphpinator\Parser\ParseResult $parseResult) : void
    {
        $this->expectException(\Exception::class);

        $normalizer = new \Graphpinator\Normalizer\Normalizer(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $operation = $operation = $normalizer->normalize($parseResult);
    }
}
