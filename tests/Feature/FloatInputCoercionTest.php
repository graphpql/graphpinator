<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class FloatInputCoercionTest extends \PHPUnit\Framework\TestCase
{
    public function testNoCoercion() : void
    {
        $query = $this->getQuery();
        $graphpinator = new \Graphpinator\Graphpinator(
            new \Graphpinator\Type\Schema(
                new \Graphpinator\Container\SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query { field(floatArg: 1.0) }',
        ])));

        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => 1.0]])->toString(),
            $result->toString(),
        );
    }

    public function testParserValue() : void
    {
        $query = $this->getQuery();
        $graphpinator = new \Graphpinator\Graphpinator(
            new \Graphpinator\Type\Schema(
                new \Graphpinator\Container\SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query { field(floatArg: 1) }',
        ])));

        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => 1.0]])->toString(),
            $result->toString(),
        );
    }

    public function testVariableValue() : void
    {
        $query = $this->getQuery();
        $graphpinator = new \Graphpinator\Graphpinator(
            new \Graphpinator\Type\Schema(
                new \Graphpinator\Container\SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query ($var: Float! = 2) { field(floatArg: $var) }',
        ])));

        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => 2.0]])->toString(),
            $result->toString(),
        );
    }

    public function testDefaultValue() : void
    {
        $query = $this->getQuery(3);
        $graphpinator = new \Graphpinator\Graphpinator(
            new \Graphpinator\Type\Schema(
                new \Graphpinator\Container\SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query { field }',
        ])));

        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => 3.0]])->toString(),
            $result->toString(),
        );
    }

    public function getQuery(?float $defaultValue = null) : \Graphpinator\Type\Type
    {
        return new class ($defaultValue) extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Query';

            public function __construct(
                private ?float $defaultValue,
            )
            {
                parent::__construct();
            }

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                $argument = \Graphpinator\Argument\Argument::create(
                    'floatArg',
                    \Graphpinator\Container\Container::Float()->notNull(),
                );

                if (\is_float($this->defaultValue)) {
                    $argument->setDefaultValue($this->defaultValue);
                }

                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'field',
                        \Graphpinator\Container\Container::Float()->notNull(),
                        static function ($parent, float $floatArg) : float {
                            return $floatArg;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        $argument,
                    ])),
                ]);
            }
        };
    }
}
