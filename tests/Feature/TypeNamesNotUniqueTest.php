<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Exception\TypeNamesNotUnique;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Type;
use PHPUnit\Framework\TestCase;

final class TypeNamesNotUniqueTest extends TestCase
{
    public function testAllSame() : void
    {
        $this->expectException(TypeNamesNotUnique::class);
        $this->expectExceptionMessage(TypeNamesNotUnique::MESSAGE);

        new SimpleContainer($this->getTypes(), []);
    }

    /**
     * @return array<Type>
     */
    private function getTypes() : array
    {
        $types = [];
        $types[] = new class extends Type {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'field',
                        Container::Int(),
                        static function () : int {
                            return 1;
                        },
                    ),
                ]);
            }
        };

        $types[] = new class extends Type {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'field',
                        Container::Int(),
                        static function () : int {
                            return 1;
                        },
                    ),
                ]);
            }
        };

        return $types;
    }
}
