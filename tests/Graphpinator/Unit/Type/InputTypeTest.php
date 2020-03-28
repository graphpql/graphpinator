<?php

declare(strict_types=1);

namespace Infinityloop\Tests\Graphpinator\Unit\Type;

final class InputTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testApplyDefaults() : void
    {
        $input = self::createTestInput();
        $value = $input->createValue(['field1' => ['subfield' => 'concrete']]);

        self::assertSame(['field1' => ['subfield' => 'concrete'], 'field2' => ['subfield' => 'random']], $value->getRawValue());
    }

    public function testInvalidValue() : void
    {
        $this->expectException(\Exception::class);

        $input = self::createTestInput();
        $input->createValue(123);
    }

    public static function createTestInput() : \Infinityloop\Graphpinator\Type\InputType
    {
        return new class extends \Infinityloop\Graphpinator\Type\InputType {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Infinityloop\Graphpinator\Argument\ArgumentSet([
                        new \Infinityloop\Graphpinator\Argument\Argument('field1', InputTypeTest::createTestSubInput(), ['subfield' => 'random']),
                        new \Infinityloop\Graphpinator\Argument\Argument('field2', InputTypeTest::createTestSubInput(), ['subfield' => 'random']),
                    ]),
                );
            }
        };
    }

    public static function createTestSubInput() : \Infinityloop\Graphpinator\Type\InputType
    {
        return new class extends \Infinityloop\Graphpinator\Type\InputType {
            protected const NAME = 'Abc';

            public function __construct()
            {
                parent::__construct(
                    new \Infinityloop\Graphpinator\Argument\ArgumentSet([new \Infinityloop\Graphpinator\Argument\Argument(
                        'subfield', \Infinityloop\Graphpinator\Type\Scalar\ScalarType::String(), 'random',
                    )]),
                );
            }
        };
    }
}
