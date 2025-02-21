<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Graphpinator;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\EnumType;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class NativeEnumTest extends TestCase
{
    public function testSimple() : void
    {
        $enumType = new class extends EnumType {
            public function __construct()
            {
                parent::__construct(self::fromEnum(NativeEnum::class));
            }
        };

        self::assertEquals(NativeEnum::class, $enumType->getEnumClass());

        $items = $enumType->getItems();

        self::assertCount(2, $items);
        self::assertArrayHasKey('ABC', $items);
        self::assertEquals('ABC', $items->offsetGet('ABC')->getName());
        self::assertEquals('Some description for following enum case', $items->offsetGet('ABC')->getDescription());
        self::assertEquals('XYZ', $items->offsetGet('XYZ')->getName());
        self::assertEquals('Another description for following enum case', $items->offsetGet('XYZ')->getDescription());

        $query = new class ($enumType) extends Type {
            protected const NAME = 'TestEnum';

            public function __construct(
                private EnumType $enumType,
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
                        $this->enumType,
                        static function ($parent, NativeEnum $arg) : NativeEnum {
                            if ($arg !== NativeEnum::ABC) {
                                throw new \RuntimeException();
                            }

                            return NativeEnum::XYZ;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'arg',
                            $this->enumType,
                        ),
                    ])),
                    ResolvableField::create(
                        'fieldDefault',
                        $this->enumType,
                        static function ($parent, NativeEnum $arg) : NativeEnum {
                            if ($arg !== NativeEnum::ABC) {
                                throw new \RuntimeException();
                            }

                            return NativeEnum::XYZ;
                        },
                    )->setArguments(new ArgumentSet([
                        Argument::create(
                            'arg',
                            $this->enumType,
                        )->setDefaultValue(NativeEnum::ABC),
                    ])),
                ]);
            }
        };
        $container = new SimpleContainer([$query, $enumType], []);
        $schema = new Schema($container, $query);

        $graphpinator = new Graphpinator($schema);
        $result = $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
             'query' => 'query { field(arg: ABC) }',
        ])));
        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => 'XYZ']])->toString(),
            $result->toString(),
        );

        $result = $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
             'query' => 'query { fieldDefault }',
        ])));
        self::assertSame(
            Json::fromNative((object) ['data' => ['fieldDefault' => 'XYZ']])->toString(),
            $result->toString(),
        );
    }
}
