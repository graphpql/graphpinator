<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

use \Graphpinator\Normalizer\Directive\Directive;
use \Graphpinator\Normalizer\Directive\DirectiveSet;
use \Graphpinator\Normalizer\Exception\ConflictingFieldDirectives;
use \Graphpinator\Normalizer\Selection\Field as NField;
use \Graphpinator\Normalizer\Selection\SelectionSet;
use \Graphpinator\Normalizer\SelectionSetValidator;
use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\Field\Field;
use \Graphpinator\Value\ArgumentValue;
use \Graphpinator\Value\ArgumentValueSet;
use \Graphpinator\Value\ScalarValue;

final class SelectionSetCanMergeTest extends \PHPUnit\Framework\TestCase
{
    public function testConflictingType() : void
    {
        $this->expectException(\Graphpinator\Normalizer\Exception\ConflictingFieldType::class);

        $field1 = new Field('field1', Container::String());
        $field2 = new Field('field2', Container::Int());
        $set = new SelectionSet([
            new NField(
                $field1,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                null,
            ),
            new NField(
                $field2,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                null,
            ),
        ]);

        $validator = new SelectionSetValidator($set);
        $validator->validate();
    }

    public function testConflictingAlias() : void
    {
        $this->expectException(\Graphpinator\Normalizer\Exception\ConflictingFieldAlias::class);

        $field1 = new Field('field1', Container::String());
        $field2 = new Field('field2', Container::String());

        $validator = new SelectionSetValidator(new SelectionSet([
            new NField(
                $field1,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                null,
            ),
            new NField(
                $field2,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                null,
            ),
        ]));

        $validator->validate();
    }

    public function testConflictingArguments() : void
    {
        $this->expectException(\Graphpinator\Normalizer\Exception\ConflictingFieldArguments::class);

        $field = new Field('field1', Container::String());
        $argument = new \Graphpinator\Typesystem\Argument\Argument('argument', Container::String());

        $validator = new SelectionSetValidator(new SelectionSet([
            new NField(
                $field,
                'someName',
                new ArgumentValueSet([
                    new ArgumentValue(
                        $argument,
                        new ScalarValue(Container::String(), '123', true),
                        true,
                    ),
                ]),
                new DirectiveSet(),
                null,
            ),
            new NField(
                $field,
                'someName',
                new ArgumentValueSet([
                    new ArgumentValue(
                        $argument,
                        new ScalarValue(Container::String(), '456', true),
                        true,
                    ),
                ]),
                new DirectiveSet(),
                null,
            ),
        ]));

        $validator->validate();
    }

    public function testConflictingDirectives() : void
    {
        $this->expectException(ConflictingFieldDirectives::class);

        $field = new Field('field1', Container::String());
        $validator = new SelectionSetValidator(new SelectionSet([
            new NField(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet([
                    new Directive(
                        Container::directiveSkip(),
                        new ArgumentValueSet([
                            new ArgumentValue(
                                Container::directiveSkip()->getArguments()['if'],
                                new ScalarValue(Container::Boolean(), false, true),
                                true,
                            ),
                        ]),
                    ),
                ]),
                null,
            ),
            new NField(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet([
                    new Directive(
                        Container::directiveInclude(),
                        new ArgumentValueSet([
                            new ArgumentValue(
                                Container::directiveInclude()->getArguments()['if'],
                                new ScalarValue(Container::Boolean(), true, true),
                                true,
                            ),
                        ]),
                    ),
                ]),
                null,
            ),
        ]));

        $validator->validate();
    }

    public function testConflictingDirectiveArguments() : void
    {
        $this->expectException(ConflictingFieldDirectives::class);

        $field = new Field('field1', Container::String());
        $validator = new SelectionSetValidator(new SelectionSet([
            new NField(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet([
                    new Directive(
                        Container::directiveSkip(),
                        new ArgumentValueSet([
                            new ArgumentValue(
                                Container::directiveSkip()->getArguments()['if'],
                                new ScalarValue(Container::Boolean(), false, true),
                                true,
                            ),
                        ]),
                    ),
                ]),
                null,
            ),
            new NField(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet([
                    new Directive(
                        Container::directiveSkip(),
                        new ArgumentValueSet([
                            new ArgumentValue(
                                Container::directiveSkip()->getArguments()['if'],
                                new ScalarValue(Container::Boolean(), true, true),
                                true,
                            ),
                        ]),
                    ),
                ]),
                null,
            ),
        ]));

        $validator->validate();
    }

    public function testConflictingDirectiveArguments2() : void
    {
        $this->expectException(ConflictingFieldDirectives::class);

        $field = new Field('field1', Container::String());
        $validator = new SelectionSetValidator(new SelectionSet([
            new NField(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet([
                    new Directive(
                        Container::directiveSkip(),
                        new ArgumentValueSet([
                            new ArgumentValue(
                                Container::directiveSkip()->getArguments()['if'],
                                new ScalarValue(Container::Boolean(), false, true),
                                true,
                            ),
                        ]),
                    ),
                ]),
                null,
            ),
            new NField(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                null,
            ),
        ]));

        $validator->validate();
    }

    public function testConflictingDirectiveArguments3() : void
    {
        $this->expectException(ConflictingFieldDirectives::class);

        $field = new Field('field1', Container::String());
        $validator = new SelectionSetValidator(new SelectionSet([
            new NField(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet([
                    new Directive(
                        Container::directiveSkip(),
                        new ArgumentValueSet([
                            new ArgumentValue(
                                Container::directiveSkip()->getArguments()['if'],
                                new ScalarValue(Container::Boolean(), true, true),
                                true,
                            ),
                        ]),
                    ),
                    new Directive(
                        Container::directiveInclude(),
                        new ArgumentValueSet([
                            new ArgumentValue(
                                Container::directiveInclude()->getArguments()['if'],
                                new ScalarValue(Container::Boolean(), true, true),
                                true,
                            ),
                        ]),
                    ),
                ]),
                null,
            ),
            new NField(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet([
                    new Directive(
                        Container::directiveSkip(),
                        new ArgumentValueSet([
                            new ArgumentValue(
                                Container::directiveSkip()->getArguments()['if'],
                                new ScalarValue(Container::Boolean(), true, true),
                                true,
                            ),
                        ]),
                    ),
                    new Directive(
                        Container::directiveInclude(),
                        new ArgumentValueSet([
                            new ArgumentValue(
                                Container::directiveInclude()->getArguments()['if'],
                                new ScalarValue(Container::Boolean(), false, true),
                                true,
                            ),
                        ]),
                    ),
                ]),
                null,
            ),
        ]));

        $validator->validate();
    }
}
