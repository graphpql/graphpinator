<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

final class SelectionSetCanMergeTest extends \PHPUnit\Framework\TestCase
{
    public function testConflictingType() : void
    {
        $this->expectException(\Graphpinator\Normalizer\Exception\ConflictingFieldType::class);

        $field1 = new \Graphpinator\Typesystem\Field\Field('field1', \Graphpinator\Typesystem\Container::String());
        $field2 = new \Graphpinator\Typesystem\Field\Field('field2', \Graphpinator\Typesystem\Container::Int());
        $set = new \Graphpinator\Normalizer\Selection\SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field1,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field2,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
        ]);

        $validator = new \Graphpinator\Normalizer\SelectionSetValidator($set);
        $validator->validate();
    }

    public function testConflictingAlias() : void
    {
        $this->expectException(\Graphpinator\Normalizer\Exception\ConflictingFieldAlias::class);

        $field1 = new \Graphpinator\Typesystem\Field\Field('field1', \Graphpinator\Typesystem\Container::String());
        $field2 = new \Graphpinator\Typesystem\Field\Field('field2', \Graphpinator\Typesystem\Container::String());

        $validator = new \Graphpinator\Normalizer\SelectionSetValidator(new \Graphpinator\Normalizer\Selection\SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field1,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field2,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
        ]));

        $validator->validate();
    }

    public function testConflictingArguments() : void
    {
        $this->expectException(\Graphpinator\Normalizer\Exception\ConflictingFieldArguments::class);

        $field = new \Graphpinator\Typesystem\Field\Field('field1', \Graphpinator\Typesystem\Container::String());
        $argument = new \Graphpinator\Typesystem\Argument\Argument('argument', \Graphpinator\Typesystem\Container::String());

        $validator = new \Graphpinator\Normalizer\SelectionSetValidator(new \Graphpinator\Normalizer\Selection\SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet([
                    new \Graphpinator\Value\ArgumentValue(
                        $argument,
                        new \Graphpinator\Value\ScalarValue(\Graphpinator\Typesystem\Container::String(), '123', true),
                        true,
                    ),
                ]),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet([
                    new \Graphpinator\Value\ArgumentValue(
                        $argument,
                        new \Graphpinator\Value\ScalarValue(\Graphpinator\Typesystem\Container::String(), '456', true),
                        true,
                    ),
                ]),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
        ]));

        $validator->validate();
    }

    public function testConflictingDirectives() : void
    {
        $this->expectException(\Graphpinator\Normalizer\Exception\ConflictingFieldDirectives::class);

        $field = new \Graphpinator\Typesystem\Field\Field('field1', \Graphpinator\Typesystem\Container::String());
        $validator = new \Graphpinator\Normalizer\SelectionSetValidator(new \Graphpinator\Normalizer\Selection\SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet([
                    new \Graphpinator\Normalizer\Directive\Directive(
                        \Graphpinator\Typesystem\Container::directiveSkip(),
                        new \Graphpinator\Value\ArgumentValueSet([
                            new \Graphpinator\Value\ArgumentValue(
                                \Graphpinator\Typesystem\Container::directiveSkip()->getArguments()['if'],
                                new \Graphpinator\Value\ScalarValue(\Graphpinator\Typesystem\Container::Boolean(), false, true),
                                true,
                            ),
                        ]),
                    ),
                ]),
                null,
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet([
                    new \Graphpinator\Normalizer\Directive\Directive(
                        \Graphpinator\Typesystem\Container::directiveInclude(),
                        new \Graphpinator\Value\ArgumentValueSet([
                            new \Graphpinator\Value\ArgumentValue(
                                \Graphpinator\Typesystem\Container::directiveInclude()->getArguments()['if'],
                                new \Graphpinator\Value\ScalarValue(\Graphpinator\Typesystem\Container::Boolean(), true, true),
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
        $this->expectException(\Graphpinator\Normalizer\Exception\ConflictingFieldDirectives::class);

        $field = new \Graphpinator\Typesystem\Field\Field('field1', \Graphpinator\Typesystem\Container::String());
        $validator = new \Graphpinator\Normalizer\SelectionSetValidator(new \Graphpinator\Normalizer\Selection\SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet([
                    new \Graphpinator\Normalizer\Directive\Directive(
                        \Graphpinator\Typesystem\Container::directiveSkip(),
                        new \Graphpinator\Value\ArgumentValueSet([
                            new \Graphpinator\Value\ArgumentValue(
                                \Graphpinator\Typesystem\Container::directiveSkip()->getArguments()['if'],
                                new \Graphpinator\Value\ScalarValue(\Graphpinator\Typesystem\Container::Boolean(), false, true),
                                true,
                            ),
                        ]),
                    ),
                ]),
                null,
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet([
                    new \Graphpinator\Normalizer\Directive\Directive(
                        \Graphpinator\Typesystem\Container::directiveSkip(),
                        new \Graphpinator\Value\ArgumentValueSet([
                            new \Graphpinator\Value\ArgumentValue(
                                \Graphpinator\Typesystem\Container::directiveSkip()->getArguments()['if'],
                                new \Graphpinator\Value\ScalarValue(\Graphpinator\Typesystem\Container::Boolean(), true, true),
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
        $this->expectException(\Graphpinator\Normalizer\Exception\ConflictingFieldDirectives::class);

        $field = new \Graphpinator\Typesystem\Field\Field('field1', \Graphpinator\Typesystem\Container::String());
        $validator = new \Graphpinator\Normalizer\SelectionSetValidator(new \Graphpinator\Normalizer\Selection\SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet([
                    new \Graphpinator\Normalizer\Directive\Directive(
                        \Graphpinator\Typesystem\Container::directiveSkip(),
                        new \Graphpinator\Value\ArgumentValueSet([
                            new \Graphpinator\Value\ArgumentValue(
                                \Graphpinator\Typesystem\Container::directiveSkip()->getArguments()['if'],
                                new \Graphpinator\Value\ScalarValue(\Graphpinator\Typesystem\Container::Boolean(), false, true),
                                true,
                            ),
                        ]),
                    ),
                ]),
                null,
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
        ]));

        $validator->validate();
    }

    public function testConflictingDirectiveArguments3() : void
    {
        $this->expectException(\Graphpinator\Normalizer\Exception\ConflictingFieldDirectives::class);

        $field = new \Graphpinator\Typesystem\Field\Field('field1', \Graphpinator\Typesystem\Container::String());
        $validator = new \Graphpinator\Normalizer\SelectionSetValidator(new \Graphpinator\Normalizer\Selection\SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet([
                    new \Graphpinator\Normalizer\Directive\Directive(
                        \Graphpinator\Typesystem\Container::directiveSkip(),
                        new \Graphpinator\Value\ArgumentValueSet([
                            new \Graphpinator\Value\ArgumentValue(
                                \Graphpinator\Typesystem\Container::directiveSkip()->getArguments()['if'],
                                new \Graphpinator\Value\ScalarValue(\Graphpinator\Typesystem\Container::Boolean(), true, true),
                                true,
                            ),
                        ]),
                    ),
                    new \Graphpinator\Normalizer\Directive\Directive(
                        \Graphpinator\Typesystem\Container::directiveInclude(),
                        new \Graphpinator\Value\ArgumentValueSet([
                            new \Graphpinator\Value\ArgumentValue(
                                \Graphpinator\Typesystem\Container::directiveInclude()->getArguments()['if'],
                                new \Graphpinator\Value\ScalarValue(\Graphpinator\Typesystem\Container::Boolean(), true, true),
                                true,
                            ),
                        ]),
                    ),
                ]),
                null,
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet([
                    new \Graphpinator\Normalizer\Directive\Directive(
                        \Graphpinator\Typesystem\Container::directiveSkip(),
                        new \Graphpinator\Value\ArgumentValueSet([
                            new \Graphpinator\Value\ArgumentValue(
                                \Graphpinator\Typesystem\Container::directiveSkip()->getArguments()['if'],
                                new \Graphpinator\Value\ScalarValue(\Graphpinator\Typesystem\Container::Boolean(), true, true),
                                true,
                            ),
                        ]),
                    ),
                    new \Graphpinator\Normalizer\Directive\Directive(
                        \Graphpinator\Typesystem\Container::directiveInclude(),
                        new \Graphpinator\Value\ArgumentValueSet([
                            new \Graphpinator\Value\ArgumentValue(
                                \Graphpinator\Typesystem\Container::directiveInclude()->getArguments()['if'],
                                new \Graphpinator\Value\ScalarValue(\Graphpinator\Typesystem\Container::Boolean(), false, true),
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
