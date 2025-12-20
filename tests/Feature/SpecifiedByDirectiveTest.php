<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Graphpinator;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\ScalarType;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class SpecifiedByDirectiveTest extends TestCase
{
    private static ?ScalarType $testScalar = null;
    private static ?Type $query = null;

    public static function createTestScalar() : ScalarType
    {
        if (self::$testScalar instanceof ScalarType) {
            return self::$testScalar;
        }

        self::$testScalar = new class extends ScalarType {
            protected const NAME = 'TestScalar';

            public function __construct()
            {
                $this->directiveUsages = new DirectiveUsageSet();

                parent::__construct();
            }

            public function initDirectiveUsages() : void
            {
                $this->setSpecifiedBy('test.test.test');
            }

            public function validateAndCoerceInput(mixed $rawValue) : mixed
            {
                return true;
            }

            public function coerceOutput(mixed $rawValue) : string|int|float|bool
            {
                return true;
            }
        };

        self::$testScalar->initDirectiveUsages();

        return self::$testScalar;
    }

    public static function typeDataProvider() : array
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
        $graphpinator = new Graphpinator($this->getSchema(), true);
        $result = $graphpinator->run(new JsonRequestFactory($request));

        self::assertSame($expected->toString(), $result->toString());
    }

    private function getSchema() : Schema
    {
        return new Schema(
            $this->getContainer(),
            $this->getQuery(),
        );
    }

    private function getContainer() : SimpleContainer
    {
        return new SimpleContainer(['TestScalar' => self::createTestScalar(), $this->getQuery()], []);
    }

    private function getQuery() : Type
    {
        if (self::$query instanceof Type) {
            return self::$query;
        }

        self::$query = new class extends Type {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    new ResolvableField(
                        'field',
                        Container::String(),
                        static function () : ?string {
                            return null;
                        },
                    ),
                ]);
            }
        };

        return self::$query;
    }
}
