<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

use Infinityloop\Utils\Json;

final class AddonTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { dateTime } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'dateTime' => '2010-01-01 12:12:50',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { date } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'date' => '2010-01-01',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { emailAddress } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'emailAddress' => 'test@test.com',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { hsla { hue saturation lightness alpha } } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'hsla' => ['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { hsl { hue saturation lightness } } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'hsl' => ['hue' => 180, 'saturation' => 50, 'lightness' => 50],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { ipv4 } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'ipv4' => '128.0.1.1',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { ipv6 } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'ipv6' => 'AAAA:1111:FFFF:9999:1111:AAAA:9999:FFFF',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { json } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'json' => '{"testName":"testValue"}',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { mac } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'mac' => 'AA:11:FF:99:11:AA',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { phoneNumber } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'phoneNumber' => '+999123456789',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { postalCode } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'postalCode' => '111 22',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { rgba { red green blue alpha } } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'rgba' => ['red' => 150, 'green' => 150, 'blue' => 150, 'alpha' => 0.5],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { rgb { red green blue } } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'rgb' => ['red' => 150, 'green' => 150, 'blue' => 150],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { time } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'time' => '12:12:50',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { url } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'url' => 'https://test.com/boo/blah.php?testValue=test&testName=name',
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { void } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'void' => null,
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { gps { lat lng } } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'gps' => ['lat' => 45.0, 'lng' => 90.0],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { point { x y } } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'point' => ['x' => 420.42, 'y' => 420.42],
                        ],
                    ],
                ]),
            ],
            [
                Json::fromNative((object) [
                    'query' => 'query queryName { fieldAddonType { bigInt } }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'bigInt' => \PHP_INT_MAX,
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param Json $request
     * @param Json $expected
     */
    public function testSimple(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }
}
