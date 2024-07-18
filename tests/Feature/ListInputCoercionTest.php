<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

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

final class ListInputCoercionTest extends TestCase
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
            'query' => 'query { field(listArg: [1, 1, 2, 3]) }',
        ])));

        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => [1, 1, 2, 3]]])->toString(),
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
            'query' => 'query { field(listArg: 5) }',
        ])));

        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => [5]]])->toString(),
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
            'query' => 'query ($var: [Int!]! = 8) { field(listArg: $var) }',
        ])));

        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => [8]]])->toString(),
            $result->toString(),
        );
    }

    public function testDefaultValue() : void
    {
        $query = $this->getQuery(12);
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
            Json::fromNative((object) ['data' => ['field' => [12]]])->toString(),
            $result->toString(),
        );
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
                    'listArg',
                    Container::Int()->notNullList(),
                );

                if (\is_int($this->defaultValue)) {
                    $argument->setDefaultValue($this->defaultValue);
                }

                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'field',
                        Container::Int()->notNullList(),
                        static function ($parent, array $listArg) : array {
                            return $listArg;
                        },
                    )->setArguments(new ArgumentSet([
                        $argument,
                    ])),
                ]);
            }
        };
    }
}
