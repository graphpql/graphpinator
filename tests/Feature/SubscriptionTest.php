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

/**
 * Adjust to correct subscription behaviour
 */
final class SubscriptionTest extends TestCase
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
                    ResolvableField::create('dummy', Container::String()->notNull(), \random_bytes(...)),
                ]);
            }
        };
        $subscription = new class extends Type {
            protected const NAME = 'Subscription';

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
                        static function ($parent) : int {
                            return 1;
                        },
                    ),
                ]);
            }
        };
        $container = new SimpleContainer([$query, $subscription], []);
        $schema = new Schema($container, $query, null, $subscription);

        $graphpinator = new Graphpinator($schema);
        $result = $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
             'query' => 'subscription { field }',
        ])));
        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => 1]])->toString(),
            $result->toString(),
        );
    }
}
