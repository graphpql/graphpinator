<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Infinityloop\Utils\Json;

final class SpecifiedByDirectiveTest extends \PHPUnit\Framework\TestCase
{
    private static ?\Graphpinator\Type\ScalarType $testScalar = null;

    private function getSchema() : \Graphpinator\Type\Schema
    {
        return new \Graphpinator\Type\Schema(
            $this->getContainer(),
            $this->getQuery(),
        );
    }

    private function getContainer() : \Graphpinator\Container\SimpleContainer
    {
        return new \Graphpinator\Container\SimpleContainer(['TestScalar' => SpecifiedByDirectiveTest::createTestScalar()], []);
    }

    private function getQuery() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Query';

            protected function getFieldDefinition() : \Graphpinator\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Field\ResolvableFieldSet([
                    new \Graphpinator\Field\ResolvableField(
                        'field',
                        \Graphpinator\Container\Container::String(),
                        static function () : void {
                        },
                    ),
                ]);
            }

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }
        };
    }

    public static function createTestScalar() : \Graphpinator\Type\ScalarType
    {
        if (self::$testScalar instanceof \Graphpinator\Type\ScalarType) {
            return self::$testScalar;
        }

        self::$testScalar = new class extends \Graphpinator\Type\ScalarType {

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
     * @param Json $request
     * @param Json $expected
     */
    public function testSpecifiedByDirective(Json $request, Json $expected) : void
    {
        $graphpinator = new \Graphpinator\Graphpinator($this->getSchema(), true);
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }
}
