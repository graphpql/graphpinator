<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Infinityloop\Utils\Json;

final class CheckForDuplicateTest extends \PHPUnit\Framework\TestCase
{
    private static ?\Graphpinator\Type\ScalarType $testDuplicateDirective = null;

    public static function createTestDuplicateDirective() : \Graphpinator\Type\ScalarType
    {
        if (self::$testDuplicateDirective instanceof \Graphpinator\Type\ScalarType) {
            return self::$testDuplicateDirective;
        }

        self::$testDuplicateDirective = new class extends \Graphpinator\Type\ScalarType {
            protected const NAME = 'TestDuplicateDirective';

            public function __construct()
            {
                $this->directiveUsages = new \Graphpinator\DirectiveUsage\DirectiveUsageSet();

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

    public function simpleDataProvider() : array
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

    private function getSchema() : \Graphpinator\Type\Schema
    {
        return new \Graphpinator\Type\Schema(
            $this->getContainer(),
            $this->getQuery(),
        );
    }

    private function getContainer() : \Graphpinator\Container\SimpleContainer
    {
        return new \Graphpinator\Container\SimpleContainer([
            'TestDuplicateDirective' => self::createTestDuplicateDirective(),
        ], []);
    }

    private function getQuery() : \Graphpinator\Type\Type
    {
        return new class extends \Graphpinator\Type\Type {
            protected const NAME = 'Query';

            public function validateNonNullValue($rawValue) : bool
            {
                return true;
            }

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
        };
    }
}
