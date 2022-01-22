<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class NativeEnumTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        if (\PHP_VERSION_ID < 80100) {
            return;
        }

        $enumType = new class extends \Graphpinator\Typesystem\EnumType {
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

        $query = new class($enumType) extends \Graphpinator\Typesystem\Type {
            public function __construct(
                private \Graphpinator\Typesystem\EnumType $enumType,
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
                        $this->enumType,
                        static function ($parent, NativeEnum $arg) : NativeEnum {
                            if ($arg !== NativeEnum::ABC) {
                                throw new \RuntimeException();
                            }

                            return NativeEnum::XYZ;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'arg',
                            $this->enumType,
                        ),
                    ])),
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'fieldDefault',
                        $this->enumType,
                        static function ($parent, NativeEnum $arg) : NativeEnum {
                            if ($arg !== NativeEnum::ABC) {
                                throw new \RuntimeException();
                            }

                            return NativeEnum::XYZ;
                        },
                    )->setArguments(new \Graphpinator\Typesystem\Argument\ArgumentSet([
                        \Graphpinator\Typesystem\Argument\Argument::create(
                            'arg',
                            $this->enumType,
                        )->setDefaultValue(NativeEnum::ABC),
                    ])),
                ]);
            }
        };
        $container = new \Graphpinator\SimpleContainer([$query, $enumType], []);
        $schema = new \Graphpinator\Typesystem\Schema($container, $query);

        $graphpinator = new \Graphpinator\Graphpinator($schema);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
             'query' => 'query { field(arg: ABC) }',
        ])));
        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => 'XYZ']])->toString(),
            $result->toString(),
        );

        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
             'query' => 'query { fieldDefault }',
        ])));
        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['fieldDefault' => 'XYZ']])->toString(),
            $result->toString(),
        );
    }
}
