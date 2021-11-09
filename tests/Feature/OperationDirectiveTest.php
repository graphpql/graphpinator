<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Graphpinator\Request\JsonRequestFactory;
use \Graphpinator\Typesystem\Container;
use \Graphpinator\Typesystem\Field\ResolvableField;
use \Graphpinator\Typesystem\Field\ResolvableFieldSet;
use \Graphpinator\Typesystem\Location\MutationLocation;
use \Graphpinator\Typesystem\Location\QueryLocation;
use \Graphpinator\Typesystem\Location\SubscriptionLocation;
use \Graphpinator\Typesystem\Type;
use \Graphpinator\Value\ArgumentValueSet;
use \Graphpinator\Value\TypeValue;
use \Infinityloop\Utils\Json;

final class OperationDirectiveTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $counter = new \stdClass();
        $counter->count = 0;

        $query = new class ($counter) extends Type {
            public function __construct(
                private \stdClass $counter,
            )
            {
                parent::__construct();
            }

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
                            return $this->counter->count;
                        },
                    ),
                ]);
            }
        };
        $mutation = new class ($counter) extends \Graphpinator\Typesystem\Type {
            public function __construct(
                private \stdClass $counter,
            )
            {
                parent::__construct();
            }

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'field',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        function ($parent) : int {
                            return $this->counter->count;
                        },
                    ),
                ]);
            }
        };
        $subscription = new class ($counter) extends \Graphpinator\Typesystem\Type {
            public function __construct(
                private \stdClass $counter,
            )
            {
                parent::__construct();
            }

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'field',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
                        function ($parent) : int {
                            return $this->counter->count;
                        },
                    ),
                ]);
            }
        };
        $test = new class ($counter) extends \Graphpinator\Typesystem\Directive implements
            QueryLocation,
            MutationLocation,
            SubscriptionLocation
        {
            protected const NAME = 'test';

            public function __construct(
                private \stdClass $counter,
            )
            {
            }

            public function resolveQueryBefore(
                ArgumentValueSet $arguments,
            ) : void
            {
                ++$this->counter->count;
            }

            public function resolveQueryAfter(
                ArgumentValueSet $arguments,
                TypeValue $typeValue,
            ) : void
            {
                ++$this->counter->count;
            }

            public function resolveMutationBefore(
                ArgumentValueSet $arguments,
            ) : void
            {
                ++$this->counter->count;
            }

            public function resolveMutationAfter(
                ArgumentValueSet $arguments,
                \Graphpinator\Value\TypeValue $typeValue,
            ) : void
            {
                ++$this->counter->count;
            }

            public function resolveSubscriptionBefore(
                ArgumentValueSet $arguments,
            ) : void
            {
                ++$this->counter->count;
            }

            public function resolveSubscriptionAfter(
                ArgumentValueSet $arguments,
                \Graphpinator\Value\TypeValue $typeValue,
            ) : void
            {
                ++$this->counter->count;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
            {
                return new \Graphpinator\Typesystem\Argument\ArgumentSet();
            }
        };
        $container = new \Graphpinator\SimpleContainer([$query], [$test]);
        $schema = new \Graphpinator\Typesystem\Schema($container, $query, $mutation, $subscription);
        $graphpinator = new \Graphpinator\Graphpinator($schema);

        $result = $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
             'query' => 'query @test { field }',
        ])));
        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => 1]])->toString(),
            $result->toString(),
        );
        self::assertSame(2, $counter->count);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(Json::fromNative((object) [
             'query' => 'mutation @test { field }',
        ])));
        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => 3]])->toString(),
            $result->toString(),
        );
        self::assertSame(4, $counter->count);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(Json::fromNative((object) [
             'query' => 'subscription @test { field }',
        ])));
        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => 5]])->toString(),
            $result->toString(),
        );
        self::assertSame(6, $counter->count);
    }
}
