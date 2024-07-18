<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\Selection\Field;
use Graphpinator\Normalizer\Selection\FragmentSpread;
use Graphpinator\Normalizer\Selection\InlineFragment;
use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\SelectionSetRefiner;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Type;
use Graphpinator\Value\ArgumentValueSet;
use PHPUnit\Framework\TestCase;

final class DuplicateFragmentSpreadModuleTest extends TestCase
{
    public function testDuplicateFragmentSpread() : void
    {
        $fragmentSpread = new FragmentSpread(
            'someName',
            new SelectionSet([
                new Field(
                    new \Graphpinator\Typesystem\Field\Field('fieldName', Container::String()),
                    'fieldName',
                    new ArgumentValueSet(),
                    new DirectiveSet(),
                ),
            ]),
            new DirectiveSet(),
            new class extends Type {
                public function validateNonNullValue(mixed $rawValue) : bool
                {
                }

                protected function getFieldDefinition() : ResolvableFieldSet
                {
                }
            },
        );

        $set = new SelectionSet([
            $fragmentSpread,
            $fragmentSpread,
        ]);

        $refiner = new SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(1, $set);
        self::assertInstanceOf(FragmentSpread::class, $set->offsetGet(0));
    }

    public function testDuplicateInnerFragmentSpread() : void
    {
        $fragmentSpread = new FragmentSpread(
            'someName',
            new SelectionSet([
                new Field(
                    new \Graphpinator\Typesystem\Field\Field('fieldName', Container::String()),
                    'fieldName',
                    new ArgumentValueSet(),
                    new DirectiveSet(),
                ),
            ]),
            new DirectiveSet(),
            new class extends Type {
                public function validateNonNullValue(mixed $rawValue) : bool
                {
                }

                protected function getFieldDefinition() : ResolvableFieldSet
                {
                }
            },
        );

        $set = new SelectionSet([
            $fragmentSpread,
            new InlineFragment(
                new SelectionSet([
                    $fragmentSpread,
                    new Field(
                        new \Graphpinator\Typesystem\Field\Field('fieldName', Container::String()),
                        'someField',
                        new ArgumentValueSet(),
                        new DirectiveSet(),
                    ),
                ]),
                new DirectiveSet(),
                null,
            ),
        ]);

        $refiner = new SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(2, $set);
        self::assertInstanceOf(FragmentSpread::class, $set->offsetGet(0));
        self::assertInstanceOf(InlineFragment::class, $set->offsetGet(1));
        self::assertCount(1, $set->offsetGet(1)->getSelections());
        self::assertInstanceOf(Field::class, $set->offsetGet(1)->getSelections()->offsetGet(1));
    }
}
