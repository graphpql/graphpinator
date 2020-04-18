<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Normalizer;

final class NormalizerTest extends \PHPUnit\Framework\TestCase
{
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

        self::assertCount(0, $operation->getFields());
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

    public function testDirectiveReferences() : void
    {
        $parseResult = new \Graphpinator\Parser\ParseResult(
            new \Graphpinator\Parser\Operation(
                new \Graphpinator\Parser\FieldSet([
                    new \Graphpinator\Parser\Field('fieldName', null, null, null, new \Graphpinator\Parser\Directive\DirectiveSet([
                        new \Graphpinator\Parser\Directive\Directive('skip', null),
                    ], \Graphpinator\Directive\DirectiveLocation::FIELD)),
                ], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([
                    new \Graphpinator\Parser\FragmentSpread\InlineFragmentSpread(
                        new \Graphpinator\Parser\FieldSet([]),
                        new \Graphpinator\Parser\Directive\DirectiveSet([
                            new \Graphpinator\Parser\Directive\Directive('skip', null),
                        ], \Graphpinator\Directive\DirectiveLocation::INLINE_FRAGMENT),
                    ),
                    new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread(
                        'fragmentName',
                        new \Graphpinator\Parser\Directive\DirectiveSet([
                            new \Graphpinator\Parser\Directive\Directive('include', null),
                        ], \Graphpinator\Directive\DirectiveLocation::FRAGMENT_SPREAD),
                    ),
                ])),
                \Graphpinator\Tokenizer\OperationType::QUERY,
                'operationName',
            ),
            new \Graphpinator\Parser\Fragment\FragmentSet([
                new \Graphpinator\Parser\Fragment\Fragment(
                    'fragmentName',
                    new \Graphpinator\Parser\TypeRef\NamedTypeRef('Int'),
                    new \Graphpinator\Parser\FieldSet([]),
                ),
            ]),
        );

        $normalizer = new \Graphpinator\Normalizer\Normalizer(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $operation = $normalizer->normalize($parseResult);

        self::assertCount(0, $operation->getVariables());
        self::assertCount(1, $operation->getFields());
        self::assertArrayHasKey('fieldName', $operation->getFields());
        self::assertSame('fieldName', $operation->getFields()->offsetGet('fieldName')->getName());
        self::assertCount(1, $operation->getFields()->offsetGet('fieldName')->getDirectives());
        self::assertArrayHasKey(0, $operation->getFields()->offsetGet('fieldName')->getDirectives());
        self::assertSame(\Graphpinator\Directive\DirectiveLocation::FIELD, $operation->getFields()->offsetGet('fieldName')->getDirectives()->getLocation());
        self::assertInstanceOf(\Graphpinator\Directive\SkipDirective::class, $operation->getFields()->offsetGet('fieldName')->getDirectives()->offsetGet(0)->getDirective());
        self::assertCount(2, $operation->getFields()->getFragments());
        self::assertArrayHasKey(0, $operation->getFields()->getFragments());
        self::assertCount(1, $operation->getFields()->getFragments()->offsetGet(0)->getDirectives());
        self::assertArrayHasKey(0, $operation->getFields()->getFragments()->offsetGet(0)->getDirectives());
        self::assertSame(\Graphpinator\Directive\DirectiveLocation::INLINE_FRAGMENT, $operation->getFields()->getFragments()->offsetGet(0)->getDirectives()->getLocation());
        self::assertInstanceOf(\Graphpinator\Directive\SkipDirective::class, $operation->getFields()->getFragments()->offsetGet(0)->getDirectives()->offsetGet(0)->getDirective());
        self::assertArrayHasKey(1, $operation->getFields()->getFragments());
        self::assertCount(1, $operation->getFields()->getFragments()->offsetGet(1)->getDirectives());
        self::assertArrayHasKey(0, $operation->getFields()->getFragments()->offsetGet(1)->getDirectives());
        self::assertSame(\Graphpinator\Directive\DirectiveLocation::FRAGMENT_SPREAD, $operation->getFields()->getFragments()->offsetGet(1)->getDirectives()->getLocation());
        self::assertInstanceOf(\Graphpinator\Directive\IncludeDirective::class, $operation->getFields()->getFragments()->offsetGet(1)->getDirectives()->offsetGet(0)->getDirective());
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
                \Graphpinator\Exception\Normalizer\OperationNotSupported::class,
            ],
            [
                new \Graphpinator\Parser\ParseResult(
                    new \Graphpinator\Parser\Operation(
                        new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([])),
                        \Graphpinator\Tokenizer\OperationType::SUBSCRIPTION,
                    ),
                    new \Graphpinator\Parser\Fragment\FragmentSet([]),
                ),
                \Graphpinator\Exception\Normalizer\OperationNotSupported::class,
            ],
            [
                new \Graphpinator\Parser\ParseResult(
                    new \Graphpinator\Parser\Operation(
                        new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([])),
                        'random',
                    ),
                    new \Graphpinator\Parser\Fragment\FragmentSet([]),
                ),
                \Graphpinator\Exception\Parser\UnknownOperationType::class,
            ],
            [
                new \Graphpinator\Parser\ParseResult(
                    new \Graphpinator\Parser\Operation(
                        new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([
                            new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragmentName')
                        ])),
                        'query',
                    ),
                    new \Graphpinator\Parser\Fragment\FragmentSet([]),
                ),
                \Graphpinator\Exception\Normalizer\UnknownFragment::class,
            ],
            [
                new \Graphpinator\Parser\ParseResult(
                    new \Graphpinator\Parser\Operation(
                        new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([])),
                        'query',
                    ),
                    new \Graphpinator\Parser\Fragment\FragmentSet([
                        new \Graphpinator\Parser\Fragment\Fragment(
                            'fragment1',
                            new \Graphpinator\Parser\TypeRef\NamedTypeRef('Int'),
                            new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([
                                new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragment5'),
                                new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragment2'),
                            ])),
                        ),
                        new \Graphpinator\Parser\Fragment\Fragment(
                            'fragment2',
                            new \Graphpinator\Parser\TypeRef\NamedTypeRef('Int'),
                            new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([
                                new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragment3')
                            ])),
                        ),
                        new \Graphpinator\Parser\Fragment\Fragment(
                            'fragment3',
                            new \Graphpinator\Parser\TypeRef\NamedTypeRef('Int'),
                            new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([
                                new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragment4')
                            ])),
                        ),
                        new \Graphpinator\Parser\Fragment\Fragment(
                            'fragment4',
                            new \Graphpinator\Parser\TypeRef\NamedTypeRef('Int'),
                            new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([
                                new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragment5'),
                                new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragment1'),
                            ])),
                        ),
                        new \Graphpinator\Parser\Fragment\Fragment(
                            'fragment5',
                            new \Graphpinator\Parser\TypeRef\NamedTypeRef('Int'),
                            new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([])),
                        ),
                    ]),
                ),
                \Graphpinator\Exception\Normalizer\FragmentCycle::class,
            ],
            [
                new \Graphpinator\Parser\ParseResult(
                    new \Graphpinator\Parser\Operation(
                        new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([])),
                        'query',
                    ),
                    new \Graphpinator\Parser\Fragment\FragmentSet([
                        new \Graphpinator\Parser\Fragment\Fragment(
                            'fragment1',
                            new \Graphpinator\Parser\TypeRef\NamedTypeRef('Int'),
                            new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([
                                new \Graphpinator\Parser\FragmentSpread\InlineFragmentSpread(new \Graphpinator\Parser\FieldSet([])),
                                new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragment2'),
                            ])),
                        ),
                        new \Graphpinator\Parser\Fragment\Fragment(
                            'fragment2',
                            new \Graphpinator\Parser\TypeRef\NamedTypeRef('Int'),
                            new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([
                                new \Graphpinator\Parser\FragmentSpread\InlineFragmentSpread(new \Graphpinator\Parser\FieldSet([])),
                                new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragment1')
                            ])),
                        ),
                    ]),
                ),
                \Graphpinator\Exception\Normalizer\FragmentCycle::class,
            ],
            [
                new \Graphpinator\Parser\ParseResult(
                    new \Graphpinator\Parser\Operation(
                        new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([])),
                        'query',
                    ),
                    new \Graphpinator\Parser\Fragment\FragmentSet([
                        new \Graphpinator\Parser\Fragment\Fragment(
                            'fragment1',
                            new \Graphpinator\Parser\TypeRef\NamedTypeRef('Int'),
                            new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([
                                new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragment2'),
                            ])),
                        ),
                        new \Graphpinator\Parser\Fragment\Fragment(
                            'fragment2',
                            new \Graphpinator\Parser\TypeRef\NamedTypeRef('Int'),
                            new \Graphpinator\Parser\FieldSet([
                                new \Graphpinator\Parser\Field(
                                    'field',
                                    null,
                                    new \Graphpinator\Parser\FieldSet([], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([
                                        new \Graphpinator\Parser\FragmentSpread\NamedFragmentSpread('fragment1')
                                    ]))
                                ),
                            ], new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet([])),
                        ),
                    ]),
                ),
                \Graphpinator\Exception\Normalizer\FragmentCycle::class,
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     */
    public function testInvalid(\Graphpinator\Parser\ParseResult $parseResult, string $exception) : void
    {
        $this->expectException($exception);
        $this->expectExceptionMessage(\constant($exception . '::MESSAGE'));

        $normalizer = new \Graphpinator\Normalizer\Normalizer(\Graphpinator\Tests\Spec\TestSchema::getSchema());
        $operation = $normalizer->normalize($parseResult);
    }
}
