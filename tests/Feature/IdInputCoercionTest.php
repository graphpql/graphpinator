<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Graphpinator\Graphpinator;
use \Graphpinator\Request\JsonRequestFactory;
use \Graphpinator\SimpleContainer;
use \Graphpinator\Typesystem\Schema;
use \Infinityloop\Utils\Json;

final class IdInputCoercionTest extends \PHPUnit\Framework\TestCase
{
    public function testNoCoercion() : void
    {
        $query = $this->getQuery();
        $graphpinator = new Graphpinator(
            new Schema(
                new SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
            'query' => 'query { field(idArg: "1") }',
        ])));

        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => '1']])->toString(),
            $result->toString(),
        );
    }

    public function testParserValue() : void
    {
        $query = $this->getQuery();
        $graphpinator = new Graphpinator(
            new Schema(
                new SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
            'query' => 'query { field(idArg: 1) }',
        ])));

        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => '1']])->toString(),
            $result->toString(),
        );
    }

    public function testVariableValue() : void
    {
        $query = $this->getQuery();
        $graphpinator = new Graphpinator(
            new Schema(
                new SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
            'query' => 'query ($var: ID! = 2) { field(idArg: $var) }',
        ])));

        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => '2']])->toString(),
            $result->toString(),
        );
    }

    public function testDefaultValue() : void
    {
        $query = $this->getQuery(3);
        $graphpinator = new Graphpinator(
            new Schema(
                new SimpleContainer([$query], []),
                $query,
            ),
        );

        $result = $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
            'query' => 'query { field }',
        ])));

        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => '3']])->toString(),
            $result->toString(),
        );
    }

    public function testNoAcceptFloat() : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $query = $this->getQuery();
        $graphpinator = new Graphpinator(
            new Schema(
                new SimpleContainer([$query], []),
                $query,
            ),
        );

        $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
            'query' => 'query { field(idArg: 1.0) }',
        ])));
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

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                $argument = \Graphpinator\Typesystem\Argument\Argument::create(
                    'idArg',
                    \Graphpinator\Typesystem\Container::Id()->notNull(),
                );

                if (\is_int($this->defaultValue)) {
                    $argument->setDefaultValue($this->defaultValue);
                }

                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'field',
                        \Graphpinator\Typesystem\Container::Id()->notNull(),
                        static function ($parent, string $idArg) : string {
                            return $idArg;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        $argument,
                    ])),
                ]);
            }
        };
    }
}
