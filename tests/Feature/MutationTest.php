<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Graphpinator\Typesystem\Field\ResolvableFieldSet;
use \Infinityloop\Utils\Json;

final class MutationTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $query = new class extends \Graphpinator\Typesystem\Type {
            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet();
            }
        };
        $mutation = new class extends \Graphpinator\Typesystem\Type {
            private int $order = 0;

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'field',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        function ($parent) : int {
                            $result = $this->order;
                            ++$this->order;

                            return $result;
                        },
                    ),
                ]);
            }
        };
        $container = new \Graphpinator\SimpleContainer([$query], []);
        $schema = new \Graphpinator\Typesystem\Schema($container, $query, $mutation);

        $graphpinator = new \Graphpinator\Graphpinator($schema);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(Json::fromNative((object) [
             'query' => 'mutation { field, secondField: field, thirdField: field }',
        ])));
        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => 0, 'secondField' => 1, 'thirdField' => 2]])->toString(),
            $result->toString(),
        );
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(Json::fromNative((object) [
             'query' => 'mutation { thirdField: field, field, secondField: field }',
        ])));
        self::assertSame(
            Json::fromNative((object) ['data' => ['thirdField' => 3, 'field' => 4, 'secondField' => 5]])->toString(),
            $result->toString(),
        );
    }
}
