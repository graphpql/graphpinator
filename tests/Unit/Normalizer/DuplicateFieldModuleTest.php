<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

final class DuplicateFieldModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testSingleField() : void
    {
        $field = new \Graphpinator\Typesystem\Field\Field('fieldName', \Graphpinator\Typesystem\Container::String());
        $set = new \Graphpinator\Normalizer\Selection\SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someOtherName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(2, $set);
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\Field::class, $set->offsetGet(0));
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\Field::class, $set->offsetGet(1));
    }

    public function testDuplicateField() : void
    {
        $field = new \Graphpinator\Typesystem\Field\Field('fieldName', \Graphpinator\Typesystem\Container::String());
        $set = new \Graphpinator\Normalizer\Selection\SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                null,
            ),
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(1, $set);
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\Field::class, $set->offsetGet(0));
    }

    public function testInnerField() : void
    {
        $field = new \Graphpinator\Typesystem\Field\Field('fieldName', \Graphpinator\Typesystem\Container::String());
        $set = new \Graphpinator\Normalizer\Selection\SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'fieldName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                new \Graphpinator\Normalizer\Selection\SelectionSet([
                    new \Graphpinator\Normalizer\Selection\Field(
                        $field,
                        'field1',
                        new \Graphpinator\Value\ArgumentValueSet(),
                        new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                        new \Graphpinator\Normalizer\Selection\SelectionSet([]),
                    ),
                ]),
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'fieldName',
                new \Graphpinator\Value\ArgumentValueSet(),
                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                new \Graphpinator\Normalizer\Selection\SelectionSet([
                    new \Graphpinator\Normalizer\Selection\Field(
                        $field,
                        'field2',
                        new \Graphpinator\Value\ArgumentValueSet(),
                        new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                        new \Graphpinator\Normalizer\Selection\SelectionSet([]),
                    ),
                ]),
            ),
        ]);

        $refiner = new \Graphpinator\Normalizer\SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(1, $set);
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\Field::class, $set->offsetGet(0));
        self::assertCount(2, $set->offsetGet(0)->getSelections());
    }
}
