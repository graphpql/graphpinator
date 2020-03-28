<?php

declare(strict_types=1);

namespace Tests\Type;

final class ListTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testResolveFields() : void
    {
        $type = new \PGQL\Type\ListType(self::getTestTypeAbc());
        $request = new \PGQL\Parser\RequestFieldSet([
            new \PGQL\Parser\RequestField('field')
        ]);
        $parent = \PGQL\Field\ResolveResult::fromRaw($type, [123, 123, 123]);
        $result = $type->resolveFields($request, $parent);

        self::assertIsArray($result);
        self::assertCount(3, $result);

        foreach ($result as $temp) {
            self::assertIsArray($temp);
            self::assertArrayHasKey('field', $temp);
            self::assertInstanceOf(\PGQL\Value\ValidatedValue::class, $temp['field']);
            self::assertSame('foo', $temp['field']->getRawValue());
        }
    }

    public function testValuePass() : void
    {
        $this->expectException(\Exception::class);

        $type = new \PGQL\Type\ListType(self::getTestTypeAbc());
        $request = new \PGQL\Parser\RequestFieldSet([
            new \PGQL\Parser\RequestField('field')
        ]);
        $parent = \PGQL\Field\ResolveResult::fromRaw($type, [124]);

        $result = $type->resolveFields($request, $parent);
    }

    public function testNoRequest() : void
    {
        $this->expectException(\Exception::class);

        $type = new \PGQL\Type\ListType(self::getTestTypeAbc());
        $parent = \PGQL\Field\ResolveResult::fromRaw($type, [124]);

        $result = $type->resolveFields(null, $parent);
    }

    public function testInvalidParentValue() : void
    {
        $this->expectException(\Exception::class);

        $type = new \PGQL\Type\ListType(self::getTestTypeAbc());
        $request = new \PGQL\Parser\RequestFieldSet([
            new \PGQL\Parser\RequestField('field')
        ]);
        $parent = \PGQL\Field\ResolveResult::fromRaw(self::getTestTypeAbc(), 124);

        $result = $type->resolveFields($request, $parent);
    }

    public static function getTestTypeAbc() : \PGQL\Type\Type
    {
        return new class extends \PGQL\Type\Type {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(new \PGQL\Field\FieldSet([new \PGQL\Field\Field(
                    'field',
                    \PGQL\Type\Scalar\ScalarType::String(),
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
