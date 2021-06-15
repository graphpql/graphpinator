<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class RootOperationTypesMustBeDifferentTest extends \PHPUnit\Framework\TestCase
{
    public function testAllSame() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\RootOperationTypesMustBeDifferent::class);
        $this->expectExceptionMessage('The query, mutation, and subscription root types must all be different types if provided.');

        $query = $this->getQuery();

        new \Graphpinator\Typesystem\Schema(
            $this->getContainer(),
            $query,
            $query,
            $query,
        );
    }

    public function testQueryMutation() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\RootOperationTypesMustBeDifferent::class);
        $query = $this->getQuery();

        new \Graphpinator\Typesystem\Schema(
            $this->getContainer(),
            $query,
            $query,
            null,
        );
    }

    public function testQuerySubscription() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\RootOperationTypesMustBeDifferent::class);
        $query = $this->getQuery();

        new \Graphpinator\Typesystem\Schema(
            $this->getContainer(),
            $query,
            null,
            $query,
        );
    }

    public function testMutationSubscription() : void
    {
        $this->expectException(\Graphpinator\Typesystem\Exception\RootOperationTypesMustBeDifferent::class);
        $query = $this->getQuery();
        $secondQuery = $this->getQuery();

        new \Graphpinator\Typesystem\Schema(
            $this->getContainer(),
            $query,
            $secondQuery,
            $secondQuery,
        );
    }

    private function getContainer() : \Graphpinator\SimpleContainer
    {
        return new \Graphpinator\SimpleContainer(['Query' => $this->getQuery()], []);
    }

    private function getQuery() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Typesystem\Container::Int(),
                        static function () : int {
                            return 1;
                        },
                    ),
                ]);
            }
        };
    }
}
