<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Constraint;

final class ObjectConstraintTest extends \PHPUnit\Framework\TestCase
{
    public function simpleObjectConstraintDataProvider() : array
    {
        return [
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1'], ['field2']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1'], ['field2']),
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1']),
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1', 'field2'], ['field1', 'field2']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1', 'field2'], ['field1', 'field2']),
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(null, ['field2']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(null, ['field2']),
                ],
            ],
        ];
    }

    public function simpleObjectConstraintDataProviderInvalid() : array
    {
        return [
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1'], ['field2']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1'], ['field3']),
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field2']),
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(null, ['field2']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(null, ['field1']),
                ],
            ],
        ];
    }

    /**
     * @dataProvider simpleObjectConstraintDataProvider
     * @param array $settings
     */
    public function testSimpleObjectConstraint(array $settings) : void
    {
        self::expectNotToPerformAssertions();
        self::getSchema($settings)->printSchema();
    }

    /**
     * @dataProvider simpleObjectConstraintDataProviderInvalid
     * @param array $settings
     */
    public function testSimpleObjectConstraintInvalid(array $settings) : void
    {
        $this->expectException(\Graphpinator\Exception\Type\ObjectConstraintsNotPreserved::class);

        self::getSchema($settings)->printSchema();
    }

    protected static function getSchema(array $settings) : \Graphpinator\Type\Schema
    {
        $interface = new class ($settings) extends \Graphpinator\Type\InterfaceType
        {
            private array $settings;

            public function __construct(
                array $settings
            )
            {
                parent::__construct();
                $this->settings = $settings;

                if (isset($this->settings['interfaceObjectConstraint'])) {
                    $this->addConstraint($this->settings['interfaceObjectConstraint']);
                }
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(\Graphpinator\Tests\Spec\TestSchema::getTypeAbc(), 123);
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                $field = new \Graphpinator\Field\Field(
                    'field1',
                    $this->settings['fieldType'],
                );

                return new \Graphpinator\Field\FieldSet([
                    $field,
                    new \Graphpinator\Field\Field(
                        'field2',
                        $this->settings['fieldType'],
                    ),
                    new \Graphpinator\Field\Field(
                        'field3',
                        $this->settings['fieldType'],
                    ),
                ]);
            }
        };

        $query = new class ($settings, $interface) extends \Graphpinator\Type\Type
        {
            protected const NAME = 'Query';
            private array $settings;

            public function __construct(
                array $settings,
                \Graphpinator\Type\InterfaceType $interface
            )
            {
                parent::__construct(new \Graphpinator\Utils\InterfaceSet([$interface]));
                $this->settings = $settings;

                if (isset($this->settings['fieldObjectConstraint'])) {
                    $this->addConstraint($this->settings['fieldObjectConstraint']);
                }
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                $field = new \Graphpinator\Field\ResolvableField(
                    'field1',
                    $this->settings['fieldType'],
                    static function() {
                        return null;
                    },
                );

                return new \Graphpinator\Field\ResolvableFieldSet([
                    $field,
                    new \Graphpinator\Field\ResolvableField(
                        'field2',
                        $this->settings['fieldType'],
                        static function() {
                            return null;
                        },
                    ),
                    new \Graphpinator\Field\ResolvableField(
                        'field3',
                        $this->settings['fieldType'],
                        static function() {
                            return null;
                        },
                    ),
                ]);
            }
        };

        return new \Graphpinator\Type\Schema(
            new \Graphpinator\Container\SimpleContainer(['query' => $query], []),
            $query,
        );
    }
}
