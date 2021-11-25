<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Normalizer;

use \Graphpinator\Normalizer\Directive\DirectiveSet;
use \Graphpinator\Normalizer\Selection\Field as NField;
use \Graphpinator\Normalizer\Selection\SelectionSet;
use \Graphpinator\Normalizer\SelectionSetRefiner;
use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\Field\Field;
use \Graphpinator\Value\ArgumentValueSet;

final class DuplicateFieldModuleTest extends \PHPUnit\Framework\TestCase
{
    public function testSingleField() : void
    {
        $field = new Field('fieldName', Container::String());
        $set = new SelectionSet([
            new NField(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                null,
            ),
            new NField(
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
        self::assertInstanceOf(NField::class, $set->offsetGet(0));
        self::assertInstanceOf(NField::class, $set->offsetGet(1));
    }

    public function testDuplicateField() : void
    {
        $field = new Field('fieldName', Container::String());
        $set = new SelectionSet([
            new NField(
                $field,
                'someName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                null,
            ),
            new NField(
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
        self::assertInstanceOf(NField::class, $set->offsetGet(0));
    }

    public function testInnerField() : void
    {
        $field = new Field('fieldName', Container::String());
        $set = new SelectionSet([
            new NField(
                $field,
                'fieldName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                new SelectionSet([
                    new NField(
                        $field,
                        'field1',
                        new ArgumentValueSet(),
                        new DirectiveSet(),
                        new SelectionSet([]),
                    ),
                ]),
            ),
            new NField(
                $field,
                'fieldName',
                new ArgumentValueSet(),
                new DirectiveSet(),
                new SelectionSet([
                    new NField(
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
        self::assertInstanceOf(NField::class, $set->offsetGet(0));
        self::assertCount(2, $set->offsetGet(0)->getSelections());
    }
}
