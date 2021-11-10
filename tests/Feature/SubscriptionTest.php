<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Graphpinator\Typesystem\Field\ResolvableFieldSet;

/**
 * Adjust to correct subscription behaviour
 */
final class SubscriptionTest extends \PHPUnit\Framework\TestCase
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
        $subscription = new class extends \Graphpinator\Typesystem\Type {
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
                        static function ($parent) : int {
                            return 1;
                        },
                    ),
                ]);
            }
        };
        $container = new \Graphpinator\SimpleContainer([$query], []);
        $schema = new \Graphpinator\Typesystem\Schema($container, $query, null, $subscription);

        $graphpinator = new \Graphpinator\Graphpinator($schema);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
             'query' => 'subscription { field }',
        ])));
        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => 1]])->toString(),
            $result->toString(),
        );
    }
}
