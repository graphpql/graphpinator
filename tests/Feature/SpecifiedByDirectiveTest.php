<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Infinityloop\Utils\Json;

final class SpecifiedByDirectiveTest extends \PHPUnit\Framework\TestCase
{
    private static ?\Graphpinator\Typesystem\ScalarType $testScalar = null;

    public static function createTestScalar() : \Graphpinator\Typesystem\ScalarType
    {
        if (self::$testScalar instanceof \Graphpinator\Typesystem\ScalarType) {
            return self::$testScalar;
        }

        self::$testScalar = new class extends \Graphpinator\Typesystem\ScalarType {
            protected const NAME = 'TestScalar';

            public function __construct()
            {
                $this->directiveUsages = new \Graphpinator\DirectiveUsage\DirectiveUsageSet();

                parent::__construct();
            }

            public function initDirectiveUsages() : void
            {
                $this->setSpecifiedBy('test.test.test');
            }

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }
        };

        self::$testScalar->initDirectiveUsages();

        return self::$testScalar;
    }

    public function typeDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{
                        __type(name: "TestScalar") { 
                            specifiedByURL
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'data' => [
                        '__type' => [
                            'specifiedByURL' => 'test.test.test',
                        ],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider typeDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $expected
     */
    public function testSpecifiedByDirective(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator($this->getSchema(), true);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    private function getSchema() : \Graphpinator\Typesystem\Schema
    {
        return new \Graphpinator\Typesystem\Schema(
            $this->getContainer(),
            $this->getQuery(),
        );
    }

    private function getContainer() : \Graphpinator\SimpleContainer
    {
        return new \Graphpinator\SimpleContainer(['TestScalar' => self::createTestScalar()], []);
    }

    private function getQuery() : \Graphpinator\Typesystem\Type
    {
        return new class extends \Graphpinator\Typesystem\Type {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    new \Graphpinator\Typesystem\Field\ResolvableField(
                        'field',
                        \Graphpinator\Typesystem\Container::String(),
                        static function () : void {
                        },
                    ),
                ]);
            }
        };
    }
}
