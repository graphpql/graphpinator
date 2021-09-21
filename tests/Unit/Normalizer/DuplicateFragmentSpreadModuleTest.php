<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

final class DuplicateFragmentSpreadModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testDuplicateFragmentSpread() : void
    {
        $fragmentSpread = new \Graphpinator\Normalizer\Selection\FragmentSpread(
            'someName',
            new \Graphpinator\Normalizer\Selection\SelectionSet([
                new \Graphpinator\Normalizer\Selection\Field(
                    new \Graphpinator\Field\Field('fieldName', \Graphpinator\Container\Container::String()),
                    'fieldName',
                    new \Graphpinator\Value\ArgumentValueSet(),
                    new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                ),
            ]),
            new \Graphpinator\Normalizer\Directive\DirectiveSet(),
            new class extends \Graphpinator\Type\Type {
                public function validateNonNullValue(mixed $rawValue) : bool
                {
                }

                protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
                {
                }
            },
        );

        $set = new \Graphpinator\Normalizer\Selection\SelectionSet([
            $fragmentSpread,
            $fragmentSpread,
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(1, $set);
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\FragmentSpread::class, $set->offsetGet(0));
    }

    public function testDuplicateInnerFragmentSpread() : void
    {
        $fragmentSpread = new \Graphpinator\Normalizer\Selection\FragmentSpread(
            'someName',
            new \Graphpinator\Normalizer\Selection\SelectionSet([
                new \Graphpinator\Normalizer\Selection\Field(
                    new \Graphpinator\Field\Field('fieldName', \Graphpinator\Container\Container::String()),
                    'fieldName',
                    new \Graphpinator\Value\ArgumentValueSet(),
                    new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                ),
            ]),
            new \Graphpinator\Normalizer\Directive\DirectiveSet(),
            new class extends \Graphpinator\Type\Type {
                public function validateNonNullValue(mixed $rawValue) : bool
                {
                }

                protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
                {
                }
            },
        );

        $set = new \Graphpinator\Normalizer\Selection\SelectionSet([
            $fragmentSpread,
            new \Graphpinator\Normalizer\Selection\InlineFragment(
                new \Graphpinator\Normalizer\Selection\SelectionSet([
                    $fragmentSpread,
                    new \Graphpinator\Normalizer\Selection\Field(
                        new \Graphpinator\Field\Field('fieldName', \Graphpinator\Container\Container::String()),
                        'someField',
                        new \Graphpinator\Value\ArgumentValueSet(),
                        new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                    ),
                ]),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(2, $set);
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\FragmentSpread::class, $set->offsetGet(0));
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\InlineFragment::class, $set->offsetGet(1));
        self::assertCount(1, $set->offsetGet(1)->getSelections());
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\Field::class, $set->offsetGet(1)->getSelections()->offsetGet(1));
    }
}
