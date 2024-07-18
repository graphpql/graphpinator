<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Exception\Value\InvalidValue;
use Graphpinator\Graphpinator;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class IdInputCoercionTest extends TestCase
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
        $this->expectException(InvalidValue::class);

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

    public function getQuery(?int $defaultValue = null) : Type
    {
        return new class ($defaultValue) extends Type
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

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                $argument = Argument::create(
                    'idArg',
                    Container::Id()->notNull(),
                );

                if (\is_int($this->defaultValue)) {
                    $argument->setDefaultValue($this->defaultValue);
                }

                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'field',
                        Container::Id()->notNull(),
                        static function ($parent, string $idArg) : string {
                            return $idArg;
                        },
                    )->setArguments(new ArgumentSet([
                        $argument,
                    ])),
                ]);
            }
        };
    }
}
