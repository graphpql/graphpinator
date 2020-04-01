<?php

declare(strict_types=1);

namespace Graphpinator\Tests\Unit\Type;

final class ListTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testResolveFields() : void
    {
        $type = self::getTestTypeAbc()->list();
        $request = new \Graphpinator\Request\FieldSet([
            new \Graphpinator\Request\Field('field')
        ]);
        $parent = \Graphpinator\Field\ResolveResult::fromRaw($type, [123, 123, 123]);
        $result = $type->resolveFields($request, $parent);

        self::assertIsArray($result);
        self::assertCount(3, $result);

        foreach ($result as $temp) {
            self::assertIsArray($temp);
            self::assertArrayHasKey('field', $temp);
            self::assertInstanceOf(\Graphpinator\Value\ValidatedValue::class, $temp['field']);
            self::assertSame('foo', $temp['field']->getRawValue());
        }
    }

    public function testValuePass() : void
    {
        $this->expectException(\Exception::class);

        $type = self::getTestTypeAbc()->list();
        $request = new \Graphpinator\Request\FieldSet([
            new \Graphpinator\Request\Field('field')
        ]);
        $parent = \Graphpinator\Field\ResolveResult::fromRaw($type, [124]);

        $result = $type->resolveFields($request, $parent);
    }

    public function testNoRequest() : void
    {
        $this->expectException(\Exception::class);

        $type = self::getTestTypeAbc()->list();
        $parent = \Graphpinator\Field\ResolveResult::fromRaw($type, [124]);

        $result = $type->resolveFields(null, $parent);
    }

    public function testInvalidParentValue() : void
    {
        $this->expectException(\Exception::class);

        $type = self::getTestTypeAbc()->list();
        $request = new \Graphpinator\Request\FieldSet([
            new \Graphpinator\Request\Field('field')
        ]);
        $parent = \Graphpinator\Field\ResolveResult::fromRaw(self::getTestTypeAbc(), 124);

        $result = $type->resolveFields($request, $parent);
    }

    public function testValidateValue() : void
    {
        $type = \Graphpinator\Type\Scalar\ScalarType::String()->list();
        self::assertNull($type->validateValue(['123', '123']));
        self::assertNull($type->validateValue(null));
    }

    public function testValidateValueInvalidValue() : void
    {
        $this->expectException(\Exception::class);

        $type = \Graphpinator\Type\Scalar\ScalarType::String()->list();
        $type->validateValue(['123', 123]);
    }

    public function testValidateValueNoArray() : void
    {
        $this->expectException(\Exception::class);

        $type = \Graphpinator\Type\Scalar\ScalarType::String()->list();
        $type->validateValue(123);
    }

    public function testInstanceOf() : void
    {
        $type = \Graphpinator\Type\Scalar\ScalarType::String()->list();

        self::assertTrue($type->isInstanceOf($type));
        self::assertTrue($type->isInstanceOf($type->notNull()));
        self::assertFalse($type->isInstanceOf(\Graphpinator\Type\Scalar\ScalarType::String()));
    }

    public static function getTestTypeAbc() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(new \Graphpinator\Field\FieldSet([new \Graphpinator\Field\Field(
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
