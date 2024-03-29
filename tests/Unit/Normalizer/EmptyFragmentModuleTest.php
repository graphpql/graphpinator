<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

final class EmptyFragmentModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testEmptyFragmentSpread() : void
    {
        $fragmentSpread = new \Graphpinator\Normalizer\Selection\FragmentSpread(
            'someName',
            new \Graphpinator\Normalizer\Selection\SelectionSet(),
            new \Graphpinator\Normalizer\Directive\DirectiveSet(),
            new class extends \Graphpinator\Typesystem\Type {
                public function validateNonNullValue(mixed $rawValue) : bool
                {
                }

                protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
                {
                }
            },
        );

        $set = new \Graphpinator\Normalizer\Selection\SelectionSet([
            $fragmentSpread,
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(0, $set);
    }

    public function testEmptyInlineFragment() : void
    {
        $inlineFragment = new \Graphpinator\Normalizer\Selection\InlineFragment(
            new \Graphpinator\Normalizer\Selection\SelectionSet(),
            new \Graphpinator\Normalizer\Directive\DirectiveSet(),
            null,
        );

        $set = new \Graphpinator\Normalizer\Selection\SelectionSet([
            $inlineFragment,
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(0, $set);
    }

    public function testEmptyCombined() : void
    {
        $fragmentSpread = new \Graphpinator\Normalizer\Selection\FragmentSpread(
            'someName',
            new \Graphpinator\Normalizer\Selection\SelectionSet(),
            new \Graphpinator\Normalizer\Directive\DirectiveSet(),
            new class extends \Graphpinator\Typesystem\Type {
                public function validateNonNullValue(mixed $rawValue) : bool
                {
                }

                protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
                {
                }
            },
        );
        $inlineFragment = new \Graphpinator\Normalizer\Selection\InlineFragment(
            new \Graphpinator\Normalizer\Selection\SelectionSet([
                $fragmentSpread,
            ]),
            new \Graphpinator\Normalizer\Directive\DirectiveSet(),
            null,
        );

        $set = new \Graphpinator\Normalizer\Selection\SelectionSet([
            $inlineFragment,
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(0, $set);
    }
}
