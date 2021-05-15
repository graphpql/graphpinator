<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class RootOperationTypesMustBeDifferentTest extends \PHPUnit\Framework\TestCase
{
    private function getContainer() : \Graphpinator\Container\SimpleContainer
    {
        return new \Graphpinator\Container\SimpleContainer(['Query' => $this->getQuery()], []);
    }

    private function getQuery() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Query';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        RootOperationTypesMustBeDifferentTest::getTypeAbc(),
                        static function () : int {
                            return 1;
                        },
                    ),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function getTypeAbc() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Abc';
            protected const DESCRIPTION = 'Test Abc description';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Container\Container::Int(),
                        static function () : void {
                        },
                    ),
                ]);
            }
        };
    }

    public function testRootOperationTypesMustBeDifferent() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\RootOperationTypesMustBeDifferent::class);
        $this->expectExceptionMessage('The query, mutation, and subscription root types must all be different types if provided.');

        $query = $this->getQuery();

        new \Graphpinator\Type\Schema(
            $this->getContainer(),
            $query,
            $query,
            $query,
        );
    }
}
