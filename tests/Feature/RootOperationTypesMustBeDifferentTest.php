<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Exception\RootOperationTypesMustBeDifferent;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use PHPUnit\Framework\TestCase;

final class RootOperationTypesMustBeDifferentTest extends TestCase
{
    public function testAllSame() : void
    {
        $this->expectException(RootOperationTypesMustBeDifferent::class);
        $this->expectExceptionMessage('The query, mutation, and subscription root types must all be different types if provided.');

        $query = $this->getQuery();

        new Schema(
            $this->getContainer(),
            $query,
            $query,
            $query,
        );
    }

    public function testQueryMutation() : void
    {
        $this->expectException(RootOperationTypesMustBeDifferent::class);
        $query = $this->getQuery();

        new Schema(
            $this->getContainer(),
            $query,
            $query,
            null,
        );
    }

    public function testQuerySubscription() : void
    {
        $this->expectException(RootOperationTypesMustBeDifferent::class);
        $query = $this->getQuery();

        new Schema(
            $this->getContainer(),
            $query,
            null,
            $query,
        );
    }

    public function testMutationSubscription() : void
    {
        $this->expectException(RootOperationTypesMustBeDifferent::class);
        $query = $this->getQuery();
        $secondQuery = $this->getQuery();

        new Schema(
            $this->getContainer(),
            $query,
            $secondQuery,
            $secondQuery,
        );
    }

    private function getContainer() : SimpleContainer
    {
        return new SimpleContainer(['Query' => $this->getQuery()], []);
    }

    private function getQuery() : Type
    {
        return new class extends Type {
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
    }
}
