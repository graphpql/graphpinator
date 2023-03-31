<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Infinityloop\Utils\Json;

final class TypeSystemDirectiveDuplicateTest extends \PHPUnit\Framework\TestCase
{
    private static ?\Graphpinator\Typesystem\ScalarType $testDuplicateDirective = null;

    public static function createTestDuplicateDirective() : \Graphpinator\Typesystem\ScalarType
    {
        if (self::$testDuplicateDirective instanceof \Graphpinator\Typesystem\ScalarType) {
            return self::$testDuplicateDirective;
        }

        self::$testDuplicateDirective = new class extends \Graphpinator\Typesystem\ScalarType {
            protected const NAME = 'TestDuplicateDirective';

            public function __construct()
            {
                $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();

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
                    'exception' => \Graphpinator\Exception\DuplicateNonRepeatableDirective::class,
                    'message' => \Graphpinator\Exception\DuplicateNonRepeatableDirective::MESSAGE,
                ]),
            ],
        ];
    }

    /**
     * @dataProvider simpleDataProvider
     * @param \Infinityloop\Utils\Json $request
     * @param \Infinityloop\Utils\Json $exception
     */
    public function testDuplicateDirective(Json $request, Json $exception) : void
    {
        $this->expectException($exception->offsetGet('exception'));
        self::assertSame('Duplicate non-repeatable directive in DirectiveUsageSet found.', $exception->offsetGet('message'));

        $graphpinator = new \Graphpinator\Graphpinator($this->getSchema(), true);
        $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory($request));
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
        return new \Graphpinator\SimpleContainer([
            'TestDuplicateDirective' => self::createTestDuplicateDirective(),
        ], []);
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
