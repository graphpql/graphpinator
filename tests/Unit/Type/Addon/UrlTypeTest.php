<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type\Addon;

final class UrlTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            ['http://foo.com/blah_blah'],
            ['http://foo.com/blah_blah/'],
            ['http://foo.com/blah_blah_(wikipedia)'],
            ['http://foo.com/blah_blah_(wikipedia)_(again)'],
            ['http://www.example.com/wpstyle/?p=364'],
            ['https://www.example.com/foo/?bar=baz&inga=42&quux'],
            ['http://userid:password@example.com:8080'],
            ['http://userid:password@example.com:8080/'],
            ['http://userid@example.com'],
            ['http://userid@example.com/'],
            ['http://userid@example.com:8080'],
            ['http://userid@example.com:8080/'],
            ['http://userid:password@example.com'],
            ['http://userid:password@example.com/'],
            ['http://142.42.1.1/'],
            ['http://142.42.1.1:8080/'],
            ['http://foo.com/blah_(wikipedia)#cite-1'],
            ['http://foo.com/blah_(wikipedia)_blah#cite-1'],
            ['http://foo.com/(something)?after=parens'],
            ['http://code.google.com/events/#&product=browser'],
            ['http://j.mp'],
            ['ftp://foo.bar/baz'],
            ['http://foo.bar/?q=Test%20URL-encoded%20stuff'],
            ["http://-.~_!$&'()*+,;=:%40:80%2f::::::@example.com"],
            ['http://1337.net'],
            ['http://a.b-c.de'],
            ['http://223.255.255.254'],
            ['rdar://1234'],
            ['h://test'],
            ['ftps://foo.bar/'],
            ['http://a.b--c.de/'],
            ['http://0.0.0.0'],
            ['http://10.1.1.0'],
            ['http://10.1.1.255'],
            ['http://224.1.1.1'],
            ['http://1.1.1.1.1'],
            ['http://123.123.123'],
            ['http://3628126748'],
            ['http://www.foo.bar./'],
            ['http://10.1.1.1'],
            ['http://10.1.1.254'],
        ];
    }

    public function invalidDataProvider() : array
    {
        return [
            ['http://✪df.ws/123'],
            ['http://➡.ws/䨹'],
            ['http://⌘.ws'],
            ['http://⌘.ws/'],
            ['http://foo.com/unicode_(✪)_in_parens'],
            ['http://☺.damowmow.com/'],
            ['http://مثال.إختبار'],
            ['http://例子.测试'],
            ['http://उदाहरण.परीक्षा'],
            ['https://foo_bar.example.com/'],
            ['http://'],
            ['http://.'],
            ['http://..'],
            ['http://../'],
            ['http://?'],
            ['http://??'],
            ['http://??/'],
            ['http://#'],
            ['http://##'],
            ['http://##/'],
            ['http://foo.bar?q=Spaces should be encoded'],
            ['//'],
            ['//a'],
            ['///a'],
            ['///'],
            ['http:///a'],
            ['foo.com'],
            ['http:// shouldfail.com'],
            [':// should fail'],
            ['http://foo.bar/foo(bar)baz quux'],
            ['http://-error-.invalid/'],
            ['http://-a.b.co'],
            ['http://a.b-.co'],
            ['http://.www.foo.bar/'],
            ['http://.www.foo.bar./'],
            [true],
            [420],
            [420.42],
            ['beetlejuice'],
            [[]],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param string $rawValue
     */
    public function testValidateValue(string $rawValue) : void
    {
        $url = new \Graphpinator\Type\Addon\UrlType();
        $value = $url->createInputedValue($rawValue);

        self::assertSame($url, $value->getType());
        self::assertSame($rawValue, $value->getRawValue());
    }

    /**
     * @dataProvider invalidDataProvider
     * @param int|bool|string|float|array $rawValue
     */
    public function testValidateValueInvalid($rawValue) : void
    {
        $this->expectException(\Graphpinator\Exception\Value\InvalidValue::class);

        $url = new \Graphpinator\Type\Addon\UrlType();
        $url->createInputedValue($rawValue);
    }
}
