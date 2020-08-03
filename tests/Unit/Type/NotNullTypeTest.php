<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type;

final class NotNullTypeTest extends \PHPUnit\Framework\TestCase
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
                        \Graphpinator\Type\Container\Container::String(),
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

    public function testCreateValue() : void
    {
        $type = self::getTestTypeAbc()->notNull();
        self::assertInstanceOf(\Graphpinator\Resolver\Value\TypeValue::class, $type->createValue(123));
    }

    public function testCreateValueNull() : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $type = self::getTestTypeAbc()->notNull();
        self::assertInstanceOf(\Graphpinator\Resolver\Value\TypeValue::class, $type->createValue(null));
    }

    public function testValidateValue() : void
    {
        $type = \Graphpinator\Type\Container\Container::String()->notNull();
        self::assertNull($type->validateValue('123'));
    }

    public function testApplyDefaults() : void
    {
        $type = \Graphpinator\Type\Container\Container::String()->notNull();
        self::assertSame('123', $type->applyDefaults('123'));
    }

    public function testValidateValueInvalidValue() : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $type = \Graphpinator\Type\Container\Container::String()->notNull();
        $type->validateValue(null);
    }

    public function testInstanceOf() : void
    {
        $type = \Graphpinator\Type\Container\Container::String()->notNull();

        self::assertTrue($type->isInstanceOf($type));
        self::assertFalse($type->isInstanceOf($type->getInnerType()));
    }
}
