<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class ListInputCoercionTest extends \PHPUnit\Framework\TestCase
{
    public function testNoCoercion() : void
    {
        $query = $this->getQuery();
        $graphpinator = new \Graphpinator\Graphpinator(
            new \Graphpinator\Typesystem\Schema(
                new \Graphpinator\SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query { field(listArg: [1, 1, 2, 3]) }',
        ])));

        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => [1, 1, 2, 3]]])->toString(),
            $result->toString(),
        );
    }

    public function testParserValue() : void
    {
        $query = $this->getQuery();
        $graphpinator = new \Graphpinator\Graphpinator(
            new \Graphpinator\Typesystem\Schema(
                new \Graphpinator\SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query { field(listArg: 5) }',
        ])));

        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => [5]]])->toString(),
            $result->toString(),
        );
    }

    public function testVariableValue() : void
    {
        $query = $this->getQuery();
        $graphpinator = new \Graphpinator\Graphpinator(
            new \Graphpinator\Typesystem\Schema(
                new \Graphpinator\SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query ($var: [Int!]! = 8) { field(listArg: $var) }',
        ])));

        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => [8]]])->toString(),
            $result->toString(),
        );
    }

    public function testDefaultValue() : void
    {
        $query = $this->getQuery(12);
        $graphpinator = new \Graphpinator\Graphpinator(
            new \Graphpinator\Typesystem\Schema(
                new \Graphpinator\SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
            'query' => 'query { field }',
        ])));

        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => [12]]])->toString(),
            $result->toString(),
        );
    }

    public function getQuery(?int $defaultValue = null) : \Graphpinator\Typesystem\Type
    {
        return new class ($defaultValue) extends \Graphpinator\Typesystem\Type
        {
            protected const NAME = 'Query';

            public function __construct(
                private ?int $defaultValue,
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
                    'listArg',
                    \Graphpinator\Typesystem\Container::Int()->notNullList(),
                );

                if (\is_int($this->defaultValue)) {
                    $argument->setDefaultValue($this->defaultValue);
                }

                return new \Graphpinator\Field\ResolvableFieldSet([
                    \Graphpinator\Field\ResolvableField::create(
                        'field',
                        \Graphpinator\Typesystem\Container::Int()->notNullList(),
                        static function ($parent, array $listArg) : array {
                            return $listArg;
                        },
                    )->setArguments(new \Graphpinator\Argument\ArgumentSet([
                        $argument,
                    ])),
                ]);
            }
        };
    }
}
