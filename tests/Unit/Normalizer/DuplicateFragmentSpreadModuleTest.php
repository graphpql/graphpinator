<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

use \Graphpinator\Normalizer\Directive\DirectiveSet;
use \Graphpinator\Normalizer\Selection\Field;
use \Graphpinator\Normalizer\Selection\FragmentSpread;
use \Graphpinator\Normalizer\Selection\SelectionSet;
use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\Field\Field as TField;
use \Graphpinator\Value\ArgumentValueSet;

final class DuplicateFragmentSpreadModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testDuplicateFragmentSpread() : void
    {
        $fragmentSpread = new FragmentSpread(
            'someName',
            new SelectionSet([
                new Field(
                    new TField('fieldName', Container::String()),
                    'fieldName',
                    new ArgumentValueSet(),
                    new DirectiveSet(),
                ),
            ]),
            new DirectiveSet(),
            new class extends \Graphpinator\Typesystem\Type {
                public function validateNonNullValue(mixed $rawValue) : bool
                {
                }

                protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
                {
                }
            },
        );

        $set = new SelectionSet([
            $fragmentSpread,
            $fragmentSpread,
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
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
                    new \Graphpinator\Typesystem\Field\Field('fieldName', \Graphpinator\Typesystem\Container::String()),
                    'fieldName',
                    new \Graphpinator\Value\ArgumentValueSet(),
                    new DirectiveSet(),
                ),
            ]),
            new DirectiveSet(),
            new class extends \Graphpinator\Typesystem\Type {
                public function validateNonNullValue(mixed $rawValue) : bool
                {
                }

                protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
                {
                }
            },
        );

        $set = new SelectionSet([
            $fragmentSpread,
            new \Graphpinator\Normalizer\Selection\InlineFragment(
                new SelectionSet([
                    $fragmentSpread,
                    new Field(
                        new \Graphpinator\Typesystem\Field\Field('fieldName', \Graphpinator\Typesystem\Container::String()),
                        'someField',
                        new \Graphpinator\Value\ArgumentValueSet(),
                        new DirectiveSet(),
                    ),
                ]),
                new DirectiveSet(),
                null,
            ),
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(2, $set);
        self::assertInstanceOf(FragmentSpread::class, $set->offsetGet(0));
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\InlineFragment::class, $set->offsetGet(1));
        self::assertCount(1, $set->offsetGet(1)->getSelections());
        self::assertInstanceOf(Field::class, $set->offsetGet(1)->getSelections()->offsetGet(1));
    }
}
