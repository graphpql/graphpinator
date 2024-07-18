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

final class FloatInputCoercionTest extends TestCase
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
            'query' => 'query { field(floatArg: 1.0) }',
        ])));

        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => 1.0]])->toString(),
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
            'query' => 'query { field(floatArg: 1) }',
        ])));

        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => 1.0]])->toString(),
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
            'query' => 'query ($var: Float! = 2) { field(floatArg: $var) }',
        ])));

        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => 2.0]])->toString(),
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
            Json::fromNative((object) ['data' => ['field' => 3.0]])->toString(),
            $result->toString(),
        );
    }

    public function getQuery(?float $defaultValue = null) : Type
    {
        return new class ($defaultValue) extends Type
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

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                $argument = Argument::create(
                    'floatArg',
                    Container::Float()->notNull(),
                );

                if (\is_float($this->defaultValue)) {
                    $argument->setDefaultValue($this->defaultValue);
                }

                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'field',
                        Container::Float()->notNull(),
                        static function ($parent, float $floatArg) : float {
                            return $floatArg;
                        },
                    )->setArguments(new ArgumentSet([
                        $argument,
                    ])),
                ]);
            }
        };
    }
}
