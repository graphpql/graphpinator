<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Type;

final class ListTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testNoRequest() : void
    {
        $this->expectException(\Exception::class);

        $type = self::getTestTypeAbc()->list();
        $parent = \Graphpinator\Resolver\FieldResult::fromRaw($type, [124]);

        $result = $type->resolve(null, $parent);
    }

    public function testValidateValue() : void
    {
        $type = \Graphpinator\Type\Container\Container::String()->list();
        self::assertNull($type->validateValue(['123', '123']));
        self::assertNull($type->validateValue(null));
    }

    public function testValidateValueInvalidValue() : void
    {
        $this->expectException(\Exception::class);

        $type = \Graphpinator\Type\Container\Container::String()->list();
        $type->validateValue(['123', 123]);
    }

    public function testValidateValueNoArray() : void
    {
        $this->expectException(\Exception::class);

        $type = \Graphpinator\Type\Container\Container::String()->list();
        $type->validateValue(123);
    }

    public function testInstanceOf() : void
    {
        $type = \Graphpinator\Type\Container\Container::String()->list();

        self::assertTrue($type->isInstanceOf($type));
        self::assertTrue($type->isInstanceOf($type->notNull()));
        self::assertFalse($type->isInstanceOf(\Graphpinator\Type\Container\Container::String()));
    }

    public static function getTestTypeAbc() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([new \Graphpinator\Field\ResolvableField(
                    'field',
                    \Graphpinator\Type\Container\Container::String(),
                    static function (int $parent) {
                        if ($parent !== 123) {
                            throw new \Exception();
                        }

                        return 'foo';
                    }
                )]);
            }
        };
    }
}
