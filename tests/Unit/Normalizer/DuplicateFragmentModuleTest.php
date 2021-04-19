<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

final class DuplicateFragmentModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testDuplicateFragmentSpread() : void
    {
        $fragmentSpread = new \Graphpinator\Normalizer\Selection\FragmentSpread(
            'someName',
            new \Graphpinator\Normalizer\Selection\SelectionSet(),
            new \Graphpinator\Normalizer\Directive\DirectiveSet(),
            new class extends \Graphpinator\Type\Type {
                public function validateNonNullValue(mixed $rawValue): bool {}
                protected function getFieldDefinition(): \Graphpinator\Field\ResolvableFieldSet {}
            },
        );

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner(new \Graphpinator\Normalizer\Selection\SelectionSet([
            $fragmentSpread,
            $fragmentSpread,
        ]), \Graphpinator\Container\Container::String());

        $result = $refiner->refine();

        self::assertCount(1, $result);
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\FragmentSpread::class, $result->offsetGet(0));
    }

    public function testDuplicateInnerFragmentSpread() : void
    {
        $fragmentSpread = new \Graphpinator\Normalizer\Selection\FragmentSpread(
            'someName',
            new \Graphpinator\Normalizer\Selection\SelectionSet(),
            new \Graphpinator\Normalizer\Directive\DirectiveSet(),
            new class extends \Graphpinator\Type\Type {
                public function validateNonNullValue(mixed $rawValue): bool {}
                protected function getFieldDefinition(): \Graphpinator\Field\ResolvableFieldSet {}
            },
        );

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner(new \Graphpinator\Normalizer\Selection\SelectionSet([
            $fragmentSpread,
            new \Graphpinator\Normalizer\Selection\InlineFragment(
                new \Graphpinator\Normalizer\Selection\SelectionSet([
                    $fragmentSpread,
                ]),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
        ]), \Graphpinator\Container\Container::String());

        $result = $refiner->refine();

        self::assertCount(2, $result);
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\FragmentSpread::class, $result->offsetGet(0));
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\InlineFragment::class, $result->offsetGet(1));
        self::assertCount(0, $result->offsetGet(1)->getSelections());
    }
}
