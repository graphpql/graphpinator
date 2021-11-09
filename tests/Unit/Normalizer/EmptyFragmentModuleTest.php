<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

use \Graphpinator\Normalizer\Directive\DirectiveSet;
use \Graphpinator\Normalizer\Selection\FragmentSpread;
use \Graphpinator\Normalizer\Selection\InlineFragment;
use \Graphpinator\Normalizer\Selection\SelectionSet;
use \Graphpinator\Normalizer\SelectionSetRefiner;
use \Graphpinator\Typesystem\Field\ResolvableFieldSet;

final class EmptyFragmentModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptyFragmentSpread() : void
    {
        $fragmentSpread = new FragmentSpread(
            'someName',
            new SelectionSet(),
            new DirectiveSet(),
            new class extends \Graphpinator\Typesystem\Type {
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
        ]);

        $refiner = new SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(0, $set);
    }

    public function testEmptyInlineFragment() : void
    {
        $inlineFragment = new InlineFragment(
            new SelectionSet(),
            new DirectiveSet(),
            null,
        );

        $set = new SelectionSet([
            $inlineFragment,
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(0, $set);
    }

    public function testEmptyCombined() : void
    {
        $fragmentSpread = new FragmentSpread(
            'someName',
            new SelectionSet(),
            new DirectiveSet(),
            new class extends \Graphpinator\Typesystem\Type {
                public function validateNonNullValue(mixed $rawValue) : bool
                {
                }

                protected function getFieldDefinition() : ResolvableFieldSet
                {
                }
            },
        );
        $inlineFragment = new InlineFragment(
            new SelectionSet([
                $fragmentSpread,
            ]),
            new DirectiveSet(),
            null,
        );

        $set = new SelectionSet([
            $inlineFragment,
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(0, $set);
    }
}
