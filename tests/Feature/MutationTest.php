<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Graphpinator;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class MutationTest extends TestCase
{
    public function testSimple() : void
    {
        $query = new class extends Type {
            protected const NAME = 'Query';

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create('dummy', Container::String(), \random_bytes(...)),
                ]);
            }
        };
        $mutation = new class extends Type {
            protected const NAME = 'Mutation';

            private int $order = 0;

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'field',
                        Container::Int()->notNull(),
                        function ($parent) : int {
                            $result = $this->order;
                            ++$this->order;

                            return $result;
                        },
                    ),
                ]);
            }
        };
        $container = new SimpleContainer([$query, $mutation], []);
        $schema = new Schema($container, $query, $mutation);

        $graphpinator = new Graphpinator($schema);
        $result = $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
             'query' => 'mutation { field, secondField: field, thirdField: field }',
        ])));
        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => 0, 'secondField' => 1, 'thirdField' => 2]])->toString(),
            $result->toString(),
        );
        $result = $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
             'query' => 'mutation { thirdField: field, field, secondField: field }',
        ])));
        self::assertSame(
            Json::fromNative((object) ['data' => ['thirdField' => 3, 'field' => 4, 'secondField' => 5]])->toString(),
            $result->toString(),
        );
    }
}
