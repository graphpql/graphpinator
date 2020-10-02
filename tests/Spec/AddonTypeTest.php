<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Spec;

final class AddonTypeTest extends \PHPUnit\Framework\TestCase
{
    public function simpleDataProvider() : array
    {
        return [
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { dateTime } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'dateTime' => '2010-01-01 12:12:50',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { date } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'date' => '2010-01-01',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { emailAddress } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'emailAddress' => 'test@test.com',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { hsla { hue saturation lightness alpha } } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'hsla' => ['hue' => 180, 'saturation' => 50, 'lightness' => 50, 'alpha' => 0.5],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { hsl { hue saturation lightness } } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'hsl' => ['hue' => 180, 'saturation' => 50, 'lightness' => 50],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { ipv4 } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'ipv4' => '128.0.1.1',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { ipv6 } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'ipv6' => 'AAAA:1111:FFFF:9999:1111:AAAA:9999:FFFF',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { json } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'json' => '{"testName":"testValue"}',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { mac } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'mac' => 'AA:11:FF:99:11:AA',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { phoneNumber } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'phoneNumber' => '+999123456789',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { postalCode } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'postalCode' => '111 22',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { rgba { red green blue alpha } } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'rgba' => ['red' => 150, 'green' => 150, 'blue' => 150, 'alpha' => 0.5],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { rgb { red green blue } } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'rgb' => ['red' => 150, 'green' => 150, 'blue' => 150],
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { time } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'time' => '12:12:50',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { url } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'url' => 'https://test.com/boo/blah.php?testValue=test&testName=name',
                        ],
                    ],
                ]),
            ],
            [
                \Graphpinator\Json::fromObject((object) [
                    'query' => 'query queryName { fieldAddonType { void } }',
                ]),
                \Graphpinator\Json::fromObject((object) [
                    'data' => [
                        'fieldAddonType' => [
                            'void' => null,
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Graphpinator\Json $request
     * @param \Graphpinator\Json $expected
     */
    public function testSimple(\Graphpinator\Json $request, \Graphpinator\Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator(TestSchema::getSchema());
        $result = $graphpinator->runQuery($request);

        self::assertSame($expected->toString(), \json_encode($result, \JSON_THROW_ON_ERROR, 512));
        self::assertSame($expected['data'], \json_decode(\json_encode($result->getData()), true));
        self::assertNull($result->getErrors());
    }
}
