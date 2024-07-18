<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Exception\DuplicateNonRepeatableDirective;
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

final class TypeSystemDirectiveDuplicateTest extends TestCase
{
    private static ?ScalarType $testDuplicateDirective = null;

    public static function createTestDuplicateDirective() : ScalarType
    {
        if (self::$testDuplicateDirective instanceof ScalarType) {
            return self::$testDuplicateDirective;
        }

        self::$testDuplicateDirective = new class extends ScalarType {
            protected const NAME = 'TestDuplicateDirective';

            public function __construct()
            {
                $this->directiveUsages = new DirectiveUsageSet();

                parent::__construct();
            }

            public function initDirectiveUsages() : void
            {
                $this->setSpecifiedBy('test.test.test');
                $this->setSpecifiedBy('invalid.invalid.invalid');
            }

            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }
        };

        self::$testDuplicateDirective->initDirectiveUsages();

        return self::$testDuplicateDirective;
    }

    public static function simpleDataProvider() : array
    {
        return [
            [
                Json::fromNative((object) [
                    'query' => '{
                        __type(name: "TestDuplicateDirective") { 
                            specifiedByURL
                        } 
                    }',
                ]),
                Json::fromNative((object) [
                    'exception' => DuplicateNonRepeatableDirective::class,
                    'message' => DuplicateNonRepeatableDirective::MESSAGE,
                ]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param Json $request
     * @param Json $exception
     */
    public function testDuplicateDirective(Json $request, Json $exception) : void
    {
        $this->expectException($exception->offsetGet('exception'));
        self::assertSame('Duplicate non-repeatable directive in DirectiveUsageSet found.', $exception->offsetGet('message'));

        $graphpinator = new Graphpinator($this->getSchema(), true);
        $graphpinator->run(new JsonRequestFactory($request));
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
        return new SimpleContainer([
            'TestDuplicateDirective' => self::createTestDuplicateDirective(),
        ], []);
    }

    private function getQuery() : Type
    {
        return new class extends Type {
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
                        static function () : void {
                        },
                    ),
                ]);
            }
        };
    }
}
