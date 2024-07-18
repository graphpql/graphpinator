<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\SelectionSetRefiner;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\Field;
use Graphpinator\Value\ArgumentValueSet;
use PHPUnit\Framework\TestCase;

final class DuplicateFieldModuleTest extends TestCase
{
    public function testSingleField() : void
    {
        $field = new Field('fieldName', Container::String());
        $set = new SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                null,
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someOtherName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                null,
            ),
        ]);

        $refiner = new SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(2, $set);
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\Field::class, $set->offsetGet(0));
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\Field::class, $set->offsetGet(1));
    }

    public function testDuplicateField() : void
    {
        $field = new Field('fieldName', Container::String());
        $set = new SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                null,
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                null,
            ),
        ]);

        $refiner = new SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(1, $set);
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\Field::class, $set->offsetGet(0));
    }

    public function testInnerField() : void
    {
        $field = new Field('fieldName', Container::String());
        $set = new SelectionSet([
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'fieldName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                new SelectionSet([
                    new \Graphpinator\Normalizer\Selection\Field(
                        $field,
                        'field1',
                        new ArgumentValueSet(),
                        new DirectiveSet(),
                        new SelectionSet([]),
                    ),
                ]),
            ),
            new \Graphpinator\Normalizer\Selection\Field(
                $field,
                'fieldName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                new SelectionSet([
                    new \Graphpinator\Normalizer\Selection\Field(
                        $field,
                        'field2',
                        new ArgumentValueSet(),
                        new DirectiveSet(),
                        new SelectionSet([]),
                    ),
                ]),
            ),
        ]);

        $refiner = new SelectionSetRefiner($set);
        $refiner->refine();

        self::assertCount(1, $set);
        self::assertInstanceOf(\Graphpinator\Normalizer\Selection\Field::class, $set->offsetGet(0));
        self::assertCount(2, $set->offsetGet(0)->getSelections());
    }
}
