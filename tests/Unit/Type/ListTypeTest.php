<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type;

final class ListTypeTest extends \PHPUnit\Framework\TestCase
{
    public static function getTestTypeAbc() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Container\Container::String(),
                        static function (int $parent) {
                            if ($parent !== 123) {
                                throw new \Exception();
                            }

                            return 'foo';
                        },
                    ),
                ]);
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public function testNoRequest() : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $type = self::getTestTypeAbc()->list();
        $parent = \Graphpinator\Resolver\FieldResult::fromRaw($type, [124]);

        $type->resolve(null, $parent);
    }

    public function testValidateValue() : void
    {
        $type = \Graphpinator\Container\Container::String()->list();
        self::assertNull($type->validateResolvedValue(['123', '123']));
        self::assertNull($type->validateResolvedValue(null));
    }

    public function testValidateValueInvalidValue() : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $type = \Graphpinator\Container\Container::String()->list();
        $type->validateResolvedValue(['123', 123]);
    }

    public function testValidateValueNoArray() : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $type = \Graphpinator\Container\Container::String()->list();
        $type->validateResolvedValue(123);
    }

    public function testInstanceOf() : void
    {
        $type = \Graphpinator\Container\Container::String()->list();

        self::assertTrue($type->isInstanceOf($type));
        self::assertTrue($type->isInstanceOf($type->notNull()));
        self::assertFalse($type->isInstanceOf(\Graphpinator\Container\Container::String()));
    }
}
