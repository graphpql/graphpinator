<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Constraint;

final class InputConstraintTest extends \PHPUnit\Framework\TestCase
{
    public function testValidateType() : void
    {
        $constraint = new \Graphpinator\Constraint\InputConstraint(['field1', 'field2']);

        self::assertTrue($constraint->validateType(new class extends \Graphpinator\Type\InputType {

            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument('field1', \Graphpinator\Container\Container::Int()),
                    new \Graphpinator\Argument\Argument('field2', \Graphpinator\Container\Container::Int()),
                ]);
            }
        }));
    }

    public function testValidateTypeInvalid() : void
    {
        $constraint = new \Graphpinator\Constraint\InputConstraint(['field1', 'field2']);

        self::assertFalse($constraint->validateType(new class extends \Graphpinator\Type\InputType {

            protected function getFieldDefinition(): \Graphpinator\Argument\ArgumentSet
            {
                return new \Graphpinator\Argument\ArgumentSet([
                    new \Graphpinator\Argument\Argument('field1', \Graphpinator\Container\Container::Int()),
                ]);
            }
        }));
    }

    public function testValidateTypeInvalid2() : void
    {
        $constraint = new \Graphpinator\Constraint\InputConstraint(['field1', 'field2']);

        self::assertFalse($constraint->validateType(\Graphpinator\Container\Container::Int()));
    }
}
