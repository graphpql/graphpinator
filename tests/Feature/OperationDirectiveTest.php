<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class OperationDirectiveTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $counter = new \stdClass();
        $counter->count = 0;

        $query = new class ($counter) extends \Graphpinator\Typesystem\Type {
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

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'field',
                        \Graphpinator\Typesystem\Container::Int()->notNull(),
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

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
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

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
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
            \Graphpinator\Typesystem\Location\QueryLocation,
            \Graphpinator\Typesystem\Location\MutationLocation,
            \Graphpinator\Typesystem\Location\SubscriptionLocation
        {
            protected const NAME = 'test';

            public function __construct(
                private \stdClass $counter,
            )
            {
            }

            public function resolveQueryBefore(
                \Graphpinator\Value\ArgumentValueSet $arguments,
            ) : void
            {
                ++$this->counter->count;
            }

            public function resolveQueryAfter(
                \Graphpinator\Value\ArgumentValueSet $arguments,
                \Graphpinator\Value\TypeValue $typeValue,
            ) : void
            {
                ++$this->counter->count;
            }

            public function resolveMutationBefore(
                \Graphpinator\Value\ArgumentValueSet $arguments,
            ) : void
            {
                ++$this->counter->count;
            }

            public function resolveMutationAfter(
                \Graphpinator\Value\ArgumentValueSet $arguments,
                \Graphpinator\Value\TypeValue $typeValue,
            ) : void
            {
                ++$this->counter->count;
            }

            public function resolveSubscriptionBefore(
                \Graphpinator\Value\ArgumentValueSet $arguments,
            ) : void
            {
                ++$this->counter->count;
            }

            public function resolveSubscriptionAfter(
                \Graphpinator\Value\ArgumentValueSet $arguments,
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

        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
             'query' => 'query @test { field }',
        ])));
        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => 1]])->toString(),
            $result->toString(),
        );
        self::assertSame(2, $counter->count);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
             'query' => 'mutation @test { field }',
        ])));
        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => 3]])->toString(),
            $result->toString(),
        );
        self::assertSame(4, $counter->count);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
             'query' => 'subscription @test { field }',
        ])));
        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => 5]])->toString(),
            $result->toString(),
        );
        self::assertSame(6, $counter->count);
    }
}
