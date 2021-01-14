<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Type;

final class InputTypeCycleTest extends \PHPUnit\Framework\TestCase
{
    public function testSimple() : void
    {
        $this->expectException(\Graphpinator\Exception\Type\InputCycle::class);
        $this->expectDeprecationMessage(\Graphpinator\Exception\Type\InputCycle::MESSAGE);

        self::getType()->getArguments();
    }

    public static function getType() : \Graphpinator\Type\InputType
    {
        static $type = null;

        if ($type === null) {
            $type = self::createType();
        }

        return $type;
    }

    private static function createType() : \Graphpinator\Type\InputType
    {
        return new class extends \Graphpinator\Type\InputType
        {
            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    \Graphpinator\Argument\Argument::create(
                        'cycle',
                        InputTypeCycleTest::getType()->notNull(),
                    ),
                ]);
            }
        };
    }
}
