<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

use Graphpinator\Normalizer\Exception\FragmentCycle;
use Graphpinator\Normalizer\Exception\OperationNotSupported;
use Graphpinator\Normalizer\Exception\UnknownFragment;
use Graphpinator\Normalizer\Normalizer;
use Graphpinator\Normalizer\Selection\Field as NormalizerField;
use Graphpinator\Normalizer\Selection\FragmentSpread;
use Graphpinator\Normalizer\Selection\InlineFragment;
use Graphpinator\Parser\Directive\Directive;
use Graphpinator\Parser\Directive\DirectiveSet;
use Graphpinator\Parser\Field\Field;
use Graphpinator\Parser\Field\FieldSet;
use Graphpinator\Parser\Fragment\Fragment;
use Graphpinator\Parser\Fragment\FragmentSet;
use Graphpinator\Parser\FragmentSpread\FragmentSpreadSet;
use Graphpinator\Parser\FragmentSpread\InlineFragmentSpread;
use Graphpinator\Parser\FragmentSpread\NamedFragmentSpread;
use Graphpinator\Parser\Operation\Operation;
use Graphpinator\Parser\Operation\OperationSet;
use Graphpinator\Parser\OperationType;
use Graphpinator\Parser\ParsedRequest;
use Graphpinator\Parser\TypeRef\ListTypeRef;
use Graphpinator\Parser\TypeRef\NamedTypeRef;
use Graphpinator\Parser\TypeRef\NotNullRef;
use Graphpinator\Parser\Value\ArgumentValue;
use Graphpinator\Parser\Value\ArgumentValueSet;
use Graphpinator\Parser\Value\Literal;
use Graphpinator\Parser\Variable\Variable;
use Graphpinator\Parser\Variable\VariableSet;
use Graphpinator\Tests\Spec\TestSchema;
use Graphpinator\Typesystem\ListType;
use Graphpinator\Typesystem\NotNullType;
use Graphpinator\Typesystem\Spec\IncludeDirective;
use Graphpinator\Typesystem\Spec\SkipDirective;
use Graphpinator\Typesystem\Visitor\GetNamedTypeVisitor;
use PHPUnit\Framework\TestCase;

final class NormalizerTest extends TestCase
{
    public static function invalidDataProvider() : array
    {
        return [
            [
                new ParsedRequest(
                    new OperationSet([
                        new Operation(
                            OperationType::MUTATION,
                            new FieldSet([], new FragmentSpreadSet()),
                        ),
                    ]),
                    new FragmentSet(),
                ),
                OperationNotSupported::class,
            ],
            [
                new ParsedRequest(
                    new OperationSet([
                        new Operation(
                            OperationType::SUBSCRIPTION,
                            new FieldSet([], new FragmentSpreadSet()),
                        ),
                    ]),
                    new FragmentSet(),
                ),
                OperationNotSupported::class,
            ],
            [
                new ParsedRequest(
                    new OperationSet([
                        new Operation(
                            OperationType::QUERY,
                            new FieldSet([], new FragmentSpreadSet([
                                new NamedFragmentSpread('fragmentName'),
                            ])),
                        ),
                    ]),
                    new FragmentSet(),
                ),
                UnknownFragment::class,
            ],
            [
                new ParsedRequest(
                    new OperationSet([
                        new Operation(
                            OperationType::QUERY,
                            new FieldSet([], new FragmentSpreadSet()),
                        ),
                    ]),
                    new FragmentSet([
                        new Fragment(
                            'fragment1',
                            new NamedTypeRef('Int'),
                            new DirectiveSet(),
                            new FieldSet([], new FragmentSpreadSet([
                                new NamedFragmentSpread('fragment5'),
                                new NamedFragmentSpread('fragment2'),
                            ])),
                        ),
                        new Fragment(
                            'fragment2',
                            new NamedTypeRef('Int'),
                            new DirectiveSet(),
                            new FieldSet([], new FragmentSpreadSet([
                                new NamedFragmentSpread('fragment3'),
                            ])),
                        ),
                        new Fragment(
                            'fragment3',
                            new NamedTypeRef('Int'),
                            new DirectiveSet(),
                            new FieldSet([], new FragmentSpreadSet([
                                new NamedFragmentSpread('fragment4'),
                            ])),
                        ),
                        new Fragment(
                            'fragment4',
                            new NamedTypeRef('Int'),
                            new DirectiveSet(),
                            new FieldSet([], new FragmentSpreadSet([
                                new NamedFragmentSpread('fragment5'),
                                new NamedFragmentSpread('fragment1'),
                            ])),
                        ),
                        new Fragment(
                            'fragment5',
                            new NamedTypeRef('Int'),
                            new DirectiveSet(),
                            new FieldSet([], new FragmentSpreadSet()),
                        ),
                    ]),
                ),
                FragmentCycle::class,
            ],
            [
                new ParsedRequest(
                    new OperationSet([
                        new Operation(
                            OperationType::QUERY,
                            new FieldSet([], new FragmentSpreadSet()),
                        ),
                    ]),
                    new FragmentSet([
                        new Fragment(
                            'fragment1',
                            new NamedTypeRef('Int'),
                            new DirectiveSet(),
                            new FieldSet([], new FragmentSpreadSet([
                                new InlineFragmentSpread(new FieldSet(
                                    [],
                                    new FragmentSpreadSet([]),
                                )),
                                new NamedFragmentSpread('fragment2'),
                            ])),
                        ),
                        new Fragment(
                            'fragment2',
                            new NamedTypeRef('Int'),
                            new DirectiveSet(),
                            new FieldSet([], new FragmentSpreadSet([
                                new InlineFragmentSpread(new FieldSet(
                                    [],
                                    new FragmentSpreadSet(),
                                )),
                                new NamedFragmentSpread('fragment1'),
                            ])),
                        ),
                    ]),
                ),
                FragmentCycle::class,
            ],
            [
                new ParsedRequest(
                    new OperationSet([
                        new Operation(
                            OperationType::QUERY,
                            new FieldSet([], new FragmentSpreadSet()),
                        ),
                    ]),
                    new FragmentSet([
                        new Fragment(
                            'fragment1',
                            new NamedTypeRef('Int'),
                            new DirectiveSet(),
                            new FieldSet([], new FragmentSpreadSet([
                                new NamedFragmentSpread('fragment2'),
                            ])),
                        ),
                        new Fragment(
                            'fragment2',
                            new NamedTypeRef('Int'),
                            new DirectiveSet(),
                            new FieldSet([
                                new Field(
                                    'field',
                                    null,
                                    new FieldSet([], new FragmentSpreadSet([
                                        new NamedFragmentSpread('fragment1'),
                                    ])),
                                ),
                            ], new FragmentSpreadSet()),
                        ),
                    ]),
                ),
                FragmentCycle::class,
            ],
        ];
    }

    public function testVariableTypeReferences() : void
    {
        $parseResult = new ParsedRequest(
            new OperationSet([
                new Operation(
                    OperationType::QUERY,
                    new FieldSet([], new FragmentSpreadSet()),
                    'operationName',
                    new VariableSet([
                        new Variable(
                            'varName',
                            new NotNullRef(new NamedTypeRef('String')),
                            null,
                            new DirectiveSet(),
                        ),
                        new Variable(
                            'varNameList',
                            new ListTypeRef(new NamedTypeRef('String')),
                            null,
                            new DirectiveSet(),
                        ),
                    ]),
                    new DirectiveSet(),
                ),
            ]),
            new FragmentSet(),
        );

        $normalizer = new Normalizer(TestSchema::getSchema());
        $operation = $normalizer->normalize($parseResult)->getOperations()->current();

        self::assertCount(0, $operation->getSelections());
        self::assertCount(2, $operation->getVariables());
        self::assertArrayHasKey('varName', $operation->getVariables());
        self::assertSame('varName', $operation->getVariables()->offsetGet('varName')->getName());
        self::assertNull($operation->getVariables()->offsetGet('varName')->getDefaultValue());
        self::assertInstanceOf(NotNullType::class, $operation->getVariables()->offsetGet('varName')->getType());
        self::assertSame('String', $operation->getVariables()->offsetGet('varName')->getType()->accept(new GetNamedTypeVisitor())->getName());
        self::assertArrayHasKey('varNameList', $operation->getVariables());
        self::assertSame('varNameList', $operation->getVariables()->offsetGet('varNameList')->getName());
        self::assertNull($operation->getVariables()->offsetGet('varNameList')->getDefaultValue());
        self::assertInstanceOf(ListType::class, $operation->getVariables()->offsetGet('varNameList')->getType());
        self::assertSame('String', $operation->getVariables()->offsetGet('varNameList')->getType()->accept(new GetNamedTypeVisitor())->getName());
    }

    public function testDirectiveReferences() : void
    {
        $parseResult = new ParsedRequest(
            new OperationSet([
                new Operation(
                    OperationType::QUERY,
                    new FieldSet([
                        new Field(
                            'fieldAbc',
                            null,
                            new FieldSet([
                                new Field('fieldXyz', null, new FieldSet([
                                    new Field('name'),
                                ], new FragmentSpreadSet())),
                            ], new FragmentSpreadSet()),
                            null,
                            new DirectiveSet([
                                new Directive(
                                    'skip',
                                    new ArgumentValueSet([
                                        new ArgumentValue(new Literal(true), 'if'),
                                    ]),
                                ),
                            ]),
                        ),
                    ], new FragmentSpreadSet([
                        new InlineFragmentSpread(
                            new FieldSet([
                                new Field('fieldListInt'),
                            ], new FragmentSpreadSet()),
                            new DirectiveSet([
                                new Directive(
                                    'skip',
                                    new ArgumentValueSet([
                                        new ArgumentValue(new Literal(true), 'if'),
                                    ]),
                                ),
                            ]),
                        ),
                        new NamedFragmentSpread(
                            'fragmentName',
                            new DirectiveSet([
                                new Directive(
                                    'include',
                                    new ArgumentValueSet([
                                        new ArgumentValue(new Literal(true), 'if'),
                                    ]),
                                ),
                            ]),
                        ),
                    ])),
                    'operationName',
                ),
            ]),
            new FragmentSet([
                new Fragment(
                    'fragmentName',
                    new NamedTypeRef('Query'),
                    new DirectiveSet(),
                    new FieldSet([
                        new Field('fieldList'),
                    ], new FragmentSpreadSet()),
                ),
            ]),
        );

        $normalizer = new Normalizer(TestSchema::getSchema());
        $operation = $normalizer->normalize($parseResult)->getOperations()->current();

        self::assertCount(0, $operation->getVariables());
        self::assertCount(3, $operation->getSelections());

        self::assertArrayHasKey(0, $operation->getSelections());
        self::assertInstanceOf(NormalizerField::class, $operation->getSelections()->offsetGet(0));
        self::assertSame('fieldAbc', $operation->getSelections()->offsetGet(0)->getName());
        self::assertCount(1, $operation->getSelections()->offsetGet(0)->getDirectives());
        self::assertArrayHasKey(0, $operation->getSelections()->offsetGet(0)->getDirectives());
        self::assertInstanceOf(
            SkipDirective::class,
            $operation->getSelections()->offsetGet(0)->getDirectives()->offsetGet(0)->getDirective(),
        );

        self::assertArrayHasKey(1, $operation->getSelections());
        self::assertInstanceOf(InlineFragment::class, $operation->getSelections()->offsetGet(1));
        self::assertSame('fieldListInt', $operation->getSelections()->offsetGet(1)->getSelections()->offsetGet(0)->getName());
        self::assertCount(1, $operation->getSelections()->offsetGet(1)->getDirectives());
        self::assertArrayHasKey(0, $operation->getSelections()->offsetGet(1)->getDirectives());
        self::assertInstanceOf(
            SkipDirective::class,
            $operation->getSelections()->offsetGet(1)->getDirectives()->offsetGet(0)->getDirective(),
        );

        self::assertArrayHasKey(2, $operation->getSelections());
        self::assertInstanceOf(FragmentSpread::class, $operation->getSelections()->offsetGet(2));
        self::assertSame('fieldList', $operation->getSelections()->offsetGet(2)->getSelections()->offsetGet(0)->getName());
        self::assertCount(1, $operation->getSelections()->offsetGet(2)->getDirectives());
        self::assertArrayHasKey(0, $operation->getSelections()->offsetGet(2)->getDirectives());
        self::assertInstanceOf(
            IncludeDirective::class,
            $operation->getSelections()->offsetGet(2)->getDirectives()->offsetGet(0)->getDirective(),
        );
    }

    /**
     * @dataProvider invalidDataProvider
     * @param ParsedRequest $parseResult
     * @param string $exception
     */
    public function testInvalid(ParsedRequest $parseResult, string $exception) : void
    {
        $this->expectException($exception);

        $normalizer = new Normalizer(TestSchema::getSchema());
        $normalizer->normalize($parseResult)->getOperations()->current();
    }
}
