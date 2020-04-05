<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Type;

final class NotNullTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateValue() : void
    {
        $type = self::getTestTypeAbc()->notNull();
        self::assertInstanceOf(\Graphpinator\Value\TypeValue::class, $type->createValue(123));
    }

    public function testCreateValueNull() : void
    {
        $this->expectException(\Exception::class);

        $type = self::getTestTypeAbc()->notNull();
        self::assertInstanceOf(\Graphpinator\Value\TypeValue::class, $type->createValue(null));
    }

    public function testValidateValue() : void
    {
        $type = \Graphpinator\Type\Scalar\ScalarType::String()->notNull();
        self::assertNull($type->validateValue('123'));
    }

    public function testApplyDefaults() : void
    {
        $type = \Graphpinator\Type\Scalar\ScalarType::String()->notNull();
        self::assertSame('123', $type->applyDefaults('123'));
    }

    public function testValidateValueInvalidValue() : void
    {
        $this->expectException(\Exception::class);

        $type = \Graphpinator\Type\Scalar\ScalarType::String()->notNull();
        $type->validateValue(null);
    }

    public function testInstanceOf() : void
    {
        $type = \Graphpinator\Type\Scalar\ScalarType::String()->notNull();

        self::assertTrue($type->isInstanceOf($type));
        self::assertFalse($type->isInstanceOf($type->getInnerType()));
    }

    public static function getTestTypeAbc() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Field\ResolvableFieldSet([new \Graphpinator\Field\ResolvableField(
                    'field',
                    \Graphpinator\Type\Scalar\ScalarType::String(),
                    static function (int $parent) {
                        if ($parent !== 123) {
                            throw new \Exception();
                        }

                        return 'foo';
                    }
                )]));
            }
        };
    }
}
