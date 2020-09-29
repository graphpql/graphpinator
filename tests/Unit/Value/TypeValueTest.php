<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Value;

final class TypeValueTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $value = new \Graphpinator\Resolver\Value\TypeValue(123, $this->createTestType());
        $value2 = \Graphpinator\Resolver\Value\TypeValue::create(123, $this->createTestType());

        self::assertSame(123, $value->getRawValue());
        self::assertSame(123, $value2->getRawValue());
    }

    public function testInvalid() : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        new \Graphpinator\Resolver\Value\TypeValue(456, $this->createTestType());
    }

    public function testPrintValue() : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Graphpinator\Exception\OperationNotSupported::class);
        $this->expectExceptionMessage('This method is not supported on this object.');

        $value = \Graphpinator\Resolver\Value\TypeValue::create(123, $this->createTestType());
        $value->printValue(true);
    }

    protected function createTestType() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            protected function validateNonNullValue($rawValue) : bool
            {
                return $rawValue === 123;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([]);
            }
        };
    }
}
