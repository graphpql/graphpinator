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
use PHPUnit\Framework\Attributes\DataProvider;
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
        $operation = $normalizer->normalize($parseResult)->operations->current();

        self::assertCount(0, $operation->children);
        self::assertCount(2, $operation->variables);
        self::assertArrayHasKey('varName', $operation->variables);
        self::assertSame('varName', $operation->variables->offsetGet('varName')->name);
        self::assertNull($operation->variables->offsetGet('varName')->defaultValue);
        self::assertInstanceOf(NotNullType::class, $operation->variables->offsetGet('varName')->type);
        self::assertSame('String', $operation->variables->offsetGet('varName')->type->accept(new GetNamedTypeVisitor())->getName());
        self::assertArrayHasKey('varNameList', $operation->variables);
        self::assertSame('varNameList', $operation->variables->offsetGet('varNameList')->name);
        self::assertNull($operation->variables->offsetGet('varNameList')->defaultValue);
        self::assertInstanceOf(ListType::class, $operation->variables->offsetGet('varNameList')->type);
        self::assertSame('String', $operation->variables->offsetGet('varNameList')->type->accept(new GetNamedTypeVisitor())->getName());
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
        $operation = $normalizer->normalize($parseResult)->operations->current();

        self::assertCount(0, $operation->variables);
        self::assertCount(3, $operation->children);

        self::assertArrayHasKey(0, $operation->children);
        self::assertInstanceOf(NormalizerField::class, $operation->children->offsetGet(0));
        self::assertSame('fieldAbc', $operation->children->offsetGet(0)->getName());
        self::assertCount(1, $operation->children->offsetGet(0)->directives);
        self::assertArrayHasKey(0, $operation->children->offsetGet(0)->directives);
        self::assertInstanceOf(
            SkipDirective::class,
            $operation->children->offsetGet(0)->directives->offsetGet(0)->directive,
        );

        self::assertArrayHasKey(1, $operation->children);
        self::assertInstanceOf(InlineFragment::class, $operation->children->offsetGet(1));
        self::assertSame('fieldListInt', $operation->children->offsetGet(1)->children->offsetGet(0)->getName());
        self::assertCount(1, $operation->children->offsetGet(1)->directives);
        self::assertArrayHasKey(0, $operation->children->offsetGet(1)->directives);
        self::assertInstanceOf(
            SkipDirective::class,
            $operation->children->offsetGet(1)->directives->offsetGet(0)->directive,
        );

        self::assertArrayHasKey(2, $operation->children);
        self::assertInstanceOf(FragmentSpread::class, $operation->children->offsetGet(2));
        self::assertSame('fieldList', $operation->children->offsetGet(2)->children->offsetGet(0)->getName());
        self::assertCount(1, $operation->children->offsetGet(2)->directives);
        self::assertArrayHasKey(0, $operation->children->offsetGet(2)->directives);
        self::assertInstanceOf(
            IncludeDirective::class,
            $operation->children->offsetGet(2)->directives->offsetGet(0)->directive,
        );
    }

    #[DataProvider('invalidDataProvider')]
    public function testInvalid(ParsedRequest $parseResult, string $exception) : void
    {
        $this->expectException($exception);

        $normalizer = new Normalizer(TestSchema::getSchema());
        $normalizer->normalize($parseResult)->operations->current();
    }
}
