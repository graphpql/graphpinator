<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Constraint;

final class FieldConstraintTest extends \PHPUnit\Framework\TestCase
{
    public function simpleStringDataProvider() : array
    {
        return [
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(2, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(2, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, null),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(2, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(null, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(2, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, 'regex40'),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, 'regex40'),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, 'regex40'),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, null),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey', 'puss in boots']),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                ],
            ],
        ];
    }

    public function simpleIntDataProvider() : array
    {
        return [
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(2, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(2, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(null, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(2, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, null),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(2, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3, 4]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [2, 3]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [4, 3, 2, 1]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, null),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [4, 3, 2, 1]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [4, 3, 2, 1]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                ],
            ],
        ];
    }

    public function simpleFloatDataProvider() : array
    {
        return [
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(2.00, 5.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(2.00, 4.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.50, 4.50),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(null, 5.00),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, null),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.15, 5.10, [1.01, 2.01, 3.01]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.15, 5.10, [1.01, 2.01, 3.01]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01, 3.01, 4.01]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [2.01, 3.01]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [4.15, 3.20, 2.30, 1.40]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [2.30, 1.40]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, null),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [2.30, 1.40]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.00, 2.00, 3.00]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.00, 2.00, 3.00]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [4.15, 3.20, 2.30, 1.40]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [3.20, 4.15, 2.30]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01, 3.01]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                ],
            ],
        ];
    }

    public function simpleListDataProvider() : array
    {
        return [
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(2, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(3, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, true),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, true),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 2,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'maxItems' => 1,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'maxItems' => 1,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'maxItems' => 1,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'maxItems' => 0,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 5,
                            'unique' => true,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 5,
                            'unique' => true,
                        ]),
                    ],
                ],
            ],
        ];
    }

    public function simpleObjectConstraintDataProvider() : array
    {
        return [
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [],
                    'fieldConstraints' => [],
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1'], ['field2']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1'], ['field2']),
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [],
                    'fieldConstraints' => [],
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1']),
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [],
                    'fieldConstraints' => [],
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
                    'interfaceFieldConstraints' => [],
                    'fieldConstraints' => [],
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1'], ['field2']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1'], ['field3']),
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [],
                    'fieldConstraints' => [],
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field1']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(['field2']),
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [],
                    'fieldConstraints' => [],
                    'interfaceObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(null, ['field2']),
                    'fieldObjectConstraint' => new \Graphpinator\Constraint\ObjectConstraint(null, ['field1']),
                ],
            ],
        ];
    }

    public function simpleInvalidDataProvider() : array
    {
        return [
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 6),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(null, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, 'regex40'),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['dog']),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 6),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(null, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(0, 6),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [4]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(0.99, 5.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.50),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(null, 5.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(0.99, 5.50),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00, [1.50, 2.50, 3.50]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00, [1.20, 2.20]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00, [1.50, 2.50, 3.50]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(1, 5, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(1, 5, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(1, 6, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 0,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 5,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 6,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'unique' => true,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'unique' => false,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => null,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceFieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'maxItems' => 1,
                        ]),
                    ],
                    'fieldConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'maxItems' => null,
                        ]),
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider simpleStringDataProvider
     * @param array $settings
     */
    public function testSimpleString(array $settings) : void
    {
        self::expectNotToPerformAssertions();
        self::getSchema($settings)->printSchema();
    }

    /**
     * @dataProvider simpleIntDataProvider
     * @param array $settings
     */
    public function testSimpleInt(array $settings) : void
    {
        self::expectNotToPerformAssertions();
        self::getSchema($settings)->printSchema();
    }

    /**
     * @dataProvider simpleFloatDataProvider
     * @param array $settings
     */
    public function testSimpleFloat(array $settings) : void
    {
        self::expectNotToPerformAssertions();
        self::getSchema($settings)->printSchema();
    }

    /**
     * @dataProvider simpleListDataProvider
     * @param array $settings
     */
    public function testSimpleList(array $settings) : void
    {
        self::expectNotToPerformAssertions();
        self::getSchema($settings)->printSchema();
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
        $this->expectException(\Graphpinator\Exception\Type\ObjectConstraintsNotEqual::class);

        self::getSchema($settings)->printSchema();
    }

    /**
     * @dataProvider simpleInvalidDataProvider
     * @param array $settings
     */
    public function testSimpleInvalid(array $settings) : void
    {
        $this->expectException(\Graphpinator\Exception\Type\FieldConstraintNotContravariant::class);

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

                foreach ($this->settings['interfaceFieldConstraints'] as $constraint) {
                    $field->addConstraint($constraint);
                }

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

            protected function validateNonNullValue($rawValue) : bool
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

                foreach ($this->settings['fieldConstraints'] as $constraint) {
                    $field->addConstraint($constraint);
                }

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
