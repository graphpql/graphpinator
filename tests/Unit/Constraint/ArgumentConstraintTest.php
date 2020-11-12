<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Unit\Constraint;

final class ArgumentConstraintTest extends \PHPUnit\Framework\TestCase
{
    public function simpleStringDataProvider() : array
    {
        return [
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 6),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(0, 6),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(null, 6),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(0, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, 'regex40'),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, 'regex40'),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(null, null, 'regex40'),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey', 'puss in boots']),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String()->list(),
                    'inputType' => \Graphpinator\Container\Container::String()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String()->list(),
                    'inputType' => \Graphpinator\Container\Container::String()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String()->list(),
                    'inputType' => \Graphpinator\Container\Container::String()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
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
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 6),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(0, 6),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(null, 6),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(0, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3, 4]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [4, 3, 2, 1]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [4, 3, 2, 1]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [4, 3, 2, 1]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
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
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.00),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.01),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(0.99, 5.02),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.01),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(0.99, 5.02),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.01),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(null, 5.02),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.01),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01, 3.01]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01, 3.01]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01, 3.01]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01, 3.01, 4.01]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01, 3.01]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [4.01, 3.01, 2.01, 1.01]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float()->list(),
                    'inputType' => \Graphpinator\Container\Container::Float()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01, 3.01]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [4.01, 3.01, 2.01, 1.01]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float()->list(),
                    'inputType' => \Graphpinator\Container\Container::Float()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01, 3.01]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [4.01, 3.01, 2.01, 1.01]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float()->list(),
                    'inputType' => \Graphpinator\Container\Container::Float()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01, 3.01]),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
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
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(2, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(1, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 6),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(null, 6),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, null),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, true),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, true),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 0,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'maxItems' => 1,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'maxItems' => 1,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'maxItems' => 4,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'maxItems' => 5,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 5,
                            'unique' => true,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 5,
                            'unique' => true,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 5,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => null,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 5,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => null,
                            'maxItems' => 5,
                        ]),
                    ],
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
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(2, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(null, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, null),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, 'regex40'),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, 'regex40'),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, 'donkeyRegex'),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey', 'puss in boots']),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String(),
                    'inputType' => \Graphpinator\Container\Container::String(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, null),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String()->list(),
                    'inputType' => \Graphpinator\Container\Container::String()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String()->list(),
                    'inputType' => \Graphpinator\Container\Container::String()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::String()->list(),
                    'inputType' => \Graphpinator\Container\Container::String()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                        new \Graphpinator\Constraint\StringConstraint(1, 5, null, ['shrek', 'fiona', 'donkey']),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(2, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(null, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, null),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2, 3]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, null),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, [1, 2]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int(),
                    'inputType' => \Graphpinator\Container\Container::Int(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, null),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\IntConstraint(1, 5, []),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.10, 5.01),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(null, 5.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01, 3.01]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, null),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, [1.01, 2.01]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, null),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.01, 5.01, []),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(null),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Float(),
                    'inputType' => \Graphpinator\Container\Container::Float(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, null),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\FloatConstraint(1.00, 5.00),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(1, 5),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 4),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, true),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, true),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 5,
                            'unique' => true,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 2,
                            'maxItems' => 5,
                            'unique' => true,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => null,
                            'maxItems' => 5,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 2,
                            'maxItems' => 5,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 2,
                            'maxItems' => null,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 2,
                            'maxItems' => 5,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 5,
                            'unique' => true,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 4,
                            'unique' => true,
                        ]),
                    ],
                ],
            ],
            [
                [
                    'fieldType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'inputType' => \Graphpinator\Container\Container::Int()->list()->list(),
                    'interfaceInputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 5,
                            'unique' => true,
                        ]),
                    ],
                    'inputConstraints' => [
                        new \Graphpinator\Constraint\ListConstraint(0, 5, false, (object) [
                            'minItems' => 1,
                            'maxItems' => 5,
                            'unique' => false,
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
     * @dataProvider simpleInvalidDataProvider
     * @param array $settings
     */
    public function testSimpleInvalid(array $settings) : void
    {
        $this->expectException(\Graphpinator\Exception\Type\ArgumentConstraintNotContravariant::class);

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
            }

            public function createResolvedValue($rawValue) : \Graphpinator\Value\TypeIntermediateValue
            {
                return new \Graphpinator\Value\TypeIntermediateValue(\Graphpinator\Tests\Spec\TestSchema::getTypeAbc(), 123);
            }

            protected function getFieldDefinition() : \Graphpinator\Field\FieldSet
            {
                $argument = new \Graphpinator\Argument\Argument(
                    'arg1',
                    $this->settings['inputType'],
                );

                foreach ($this->settings['interfaceInputConstraints'] as $constraint) {
                    $argument->addConstraint($constraint);
                }

                return new \Graphpinator\Field\FieldSet([
                    new \Graphpinator\Field\Field(
                        'field1',
                        $this->settings['fieldType'],
                        new \Graphpinator\Argument\ArgumentSet([
                            $argument,
                        ]),
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
            }

            protected function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                $argument = new \Graphpinator\Argument\Argument(
                    'arg1',
                    $this->settings['inputType'],
                );

                foreach ($this->settings['inputConstraints'] as $constraint) {
                    $argument->addConstraint($constraint);
                }

                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field1',
                        $this->settings['fieldType'],
                        static function() {
                            return null;
                        },
                        new \Graphpinator\Argument\ArgumentSet([
                            $argument,
                        ]),
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
