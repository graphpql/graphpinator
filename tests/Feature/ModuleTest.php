<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

final class ModuleTest extends \PHPUnit\Framework\TestCase
{
    public static function moduleDataProvider() : array
    {
        return [
            [
                new class implements \Graphpinator\Module\Module {
                    private \stdClass $counter;
                    private \Graphpinator\Typesystem\Type $query;

                    public function setCounter(\stdClass $counter) : void
                    {
                        $this->counter = $counter;
                    }

                    public function setQuery(\Graphpinator\Typesystem\Type $type) : void
                    {
                        $this->query = $type;
                    }

                    public function processRequest(
                        \Graphpinator\Request\Request $request,
                    ) : \Graphpinator\Request\Request
                    {
                        ++$this->counter->count;

                        return $request;
                    }

                    public function processParsed(
                        \Graphpinator\Parser\ParsedRequest $request,
                    ) : \Graphpinator\Parser\ParsedRequest
                    {
                        $this->counter->count += 2;

                        return $request;
                    }

                    public function processNormalized(
                        \Graphpinator\Normalizer\NormalizedRequest $request,
                    ) : \Graphpinator\Normalizer\NormalizedRequest
                    {
                        $this->counter->count += 4;

                        return $request;
                    }

                    public function processFinalized(
                        \Graphpinator\Normalizer\FinalizedRequest $request,
                    ) : \Graphpinator\Normalizer\FinalizedRequest
                    {
                        $this->counter->count += 8;

                        return $request;
                    }

                    public function processResult(
                        \Graphpinator\Result $result,
                    ) : \Graphpinator\Result
                    {
                        $this->counter->count += 16;

                        return $result;
                    }
                },
                31,
            ],
            [
                new class implements \Graphpinator\Module\Module {
                    private \stdClass $counter;
                    private \Graphpinator\Typesystem\Type $query;

                    public function setCounter(\stdClass $counter) : void
                    {
                        $this->counter = $counter;
                    }

                    public function setQuery(\Graphpinator\Typesystem\Type $type) : void
                    {
                        $this->query = $type;
                    }

                    public function processRequest(
                        \Graphpinator\Request\Request $request,
                    ) : \Graphpinator\Parser\ParsedRequest
                    {
                        ++$this->counter->count;

                        return new \Graphpinator\Parser\ParsedRequest(
                            new \Graphpinator\Parser\Operation\OperationSet([
                                new \Graphpinator\Parser\Operation\Operation(
                                    'query',
                                    null,
                                    null,
                                    null,
                                    new \Graphpinator\Parser\Field\FieldSet(
                                        [new \Graphpinator\Parser\Field\Field('field')],
                                        new \Graphpinator\Parser\FragmentSpread\FragmentSpreadSet(),
                                    ),
                                ),
                            ]),
                            new \Graphpinator\Parser\Fragment\FragmentSet(),
                        );
                    }

                    public function processParsed(
                        \Graphpinator\Parser\ParsedRequest $request,
                    ) : \Graphpinator\Parser\ParsedRequest
                    {
                        $this->counter->count += 2;

                        return $request;
                    }

                    public function processNormalized(
                        \Graphpinator\Normalizer\NormalizedRequest $request,
                    ) : \Graphpinator\Normalizer\NormalizedRequest
                    {
                        $this->counter->count += 4;

                        return $request;
                    }

                    public function processFinalized(
                        \Graphpinator\Normalizer\FinalizedRequest $request,
                    ) : \Graphpinator\Normalizer\FinalizedRequest
                    {
                        $this->counter->count += 8;

                        return $request;
                    }

                    public function processResult(
                        \Graphpinator\Result $result,
                    ) : \Graphpinator\Result
                    {
                        $this->counter->count += 16;

                        return $result;
                    }
                },
                29,
            ],
            [
                new class implements \Graphpinator\Module\Module {
                    private \stdClass $counter;
                    private \Graphpinator\Typesystem\Type $query;

                    public function setCounter(\stdClass $counter) : void
                    {
                        $this->counter = $counter;
                    }

                    public function setQuery(\Graphpinator\Typesystem\Type $type) : void
                    {
                        $this->query = $type;
                    }

                    public function processRequest(
                        \Graphpinator\Request\Request $request,
                    ) : \Graphpinator\Request\Request
                    {
                        ++$this->counter->count;

                        return $request;
                    }

                    public function processParsed(
                        \Graphpinator\Parser\ParsedRequest $request,
                    ) : \Graphpinator\Normalizer\NormalizedRequest
                    {
                        $this->counter->count += 2;

                        return new \Graphpinator\Normalizer\NormalizedRequest(
                            new \Graphpinator\Normalizer\Operation\OperationSet([
                                new \Graphpinator\Normalizer\Operation\Operation(
                                    'query',
                                    null,
                                    $this->query,
                                    new \Graphpinator\Normalizer\Selection\SelectionSet([
                                        new \Graphpinator\Normalizer\Selection\Field(
                                            $this->query->getFields()['field'],
                                            'field',
                                            new \Graphpinator\Value\ArgumentValueSet(),
                                            new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                                        ),
                                    ]),
                                    new \Graphpinator\Normalizer\Variable\VariableSet(),
                                    new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                                ),
                            ]),
                        );
                    }

                    public function processNormalized(
                        \Graphpinator\Normalizer\NormalizedRequest $request,
                    ) : \Graphpinator\Normalizer\NormalizedRequest
                    {
                        $this->counter->count += 4;

                        return $request;
                    }

                    public function processFinalized(
                        \Graphpinator\Normalizer\FinalizedRequest $request,
                    ) : \Graphpinator\Normalizer\FinalizedRequest
                    {
                        $this->counter->count += 8;

                        return $request;
                    }

                    public function processResult(
                        \Graphpinator\Result $result,
                    ) : \Graphpinator\Result
                    {
                        $this->counter->count += 16;

                        return $result;
                    }
                },
                27,
            ],
            [
                new class implements \Graphpinator\Module\Module {
                    private \stdClass $counter;
                    private \Graphpinator\Typesystem\Type $query;

                    public function setCounter(\stdClass $counter) : void
                    {
                        $this->counter = $counter;
                    }

                    public function setQuery(\Graphpinator\Typesystem\Type $type) : void
                    {
                        $this->query = $type;
                    }

                    public function processRequest(
                        \Graphpinator\Request\Request $request,
                    ) : \Graphpinator\Request\Request
                    {
                        ++$this->counter->count;

                        return $request;
                    }

                    public function processParsed(
                        \Graphpinator\Parser\ParsedRequest $request,
                    ) : \Graphpinator\Parser\ParsedRequest
                    {
                        $this->counter->count += 2;

                        return $request;
                    }

                    public function processNormalized(
                        \Graphpinator\Normalizer\NormalizedRequest $request,
                    ) : \Graphpinator\Normalizer\FinalizedRequest
                    {
                        $this->counter->count += 4;

                        return new \Graphpinator\Normalizer\FinalizedRequest(
                            new \Graphpinator\Normalizer\Operation\Operation(
                                'query',
                                null,
                                $this->query,
                                new \Graphpinator\Normalizer\Selection\SelectionSet([
                                    new \Graphpinator\Normalizer\Selection\Field(
                                        $this->query->getFields()['field'],
                                        'field',
                                        new \Graphpinator\Value\ArgumentValueSet(),
                                        new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                                    ),
                                ]),
                                new \Graphpinator\Normalizer\Variable\VariableSet(),
                                new \Graphpinator\Normalizer\Directive\DirectiveSet(),
                            ),
                        );
                    }

                    public function processFinalized(
                        \Graphpinator\Normalizer\FinalizedRequest $request,
                    ) : \Graphpinator\Normalizer\FinalizedRequest
                    {
                        $this->counter->count += 8;

                        return $request;
                    }

                    public function processResult(
                        \Graphpinator\Result $result,
                    ) : \Graphpinator\Result
                    {
                        $this->counter->count += 16;

                        return $result;
                    }
                },
                23,
            ],
        ];
    }

    /**
     * @dataProvider moduleDataProvider
     */
    public function testSimple(\Graphpinator\Module\Module $module, int $expectedCount) : void
    {
        $query = new class extends \Graphpinator\Typesystem\Type {
            public function validateNonNullValue(mixed $rawValue) : bool
            {
                return true;
            }

            protected function getFieldDefinition() : \Graphpinator\Typesystem\Field\ResolvableFieldSet
            {
                return new \Graphpinator\Typesystem\Field\ResolvableFieldSet([
                    \Graphpinator\Typesystem\Field\ResolvableField::create(
                        'field',
                        \Graphpinator\Typesystem\Container::String()->notNull(),
                        static function ($parent) : string {
                            return 'test';
                        },
                    ),
                ]);
            }
        };
        $container = new \Graphpinator\SimpleContainer([$query], []);
        $schema = new \Graphpinator\Typesystem\Schema($container, $query);

        $counter = new \stdClass();
        $counter->count = 0;
        $module->setCounter($counter);
        $module->setQuery($query);

        $graphpinator = new \Graphpinator\Graphpinator($schema, false, new \Graphpinator\Module\ModuleSet([$module]));
        $result = $graphpinator->run(new \Graphpinator\Request\JsonRequestFactory(\Infinityloop\Utils\Json::fromNative((object) [
             'query' => '{ field }',
        ])));
        self::assertSame(
            \Infinityloop\Utils\Json::fromNative((object) ['data' => ['field' => 'test']])->toString(),
            $result->toString(),
        );
        self::assertSame($expectedCount, $counter->count);
    }
}
