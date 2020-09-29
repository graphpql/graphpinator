<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class JsonTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['{"class":"test"}'],
            ['{"testName":"testValue"}'],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            [['class' => "\xB1\x31"]],
            ['{class: test}'],
            [true],
            [420],
            [420.42],
            ['beetlejuice'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string|array $rawValue
     */
    public function testValidateValue($rawValue) : void
    {
        $json = new \Graphpinator\Type\Addon\JsonType();
        $json->validateValue($rawValue);

        self::assertSame($json->getName(), 'json');
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array|object $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        //phpcs:ignore SlevomatCodingStandard.Exceptions.ReferenceThrowableOnly.ReferencedGeneralException
        $this->expectException(\Exception::class);

        $json = new \Graphpinator\Type\Addon\JsonType();
        $json->validateValue($rawValue);
    }
}
