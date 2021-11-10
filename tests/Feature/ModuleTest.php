<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use \Graphpinator\Module\Module;
use \Graphpinator\Normalizer\Directive\DirectiveSet;
use \Graphpinator\Normalizer\FinalizedRequest;
use \Graphpinator\Normalizer\NormalizedRequest;
use \Graphpinator\Parser\ParsedRequest;
use \Graphpinator\Request\Request;
use \Graphpinator\Result;
use \Graphpinator\Typesystem\Type;

final class ModuleTest extends \PHPUnit\Framework\TestCase
{
    public function moduleDataProvider() : array
    {
        return [
            [
                new class implements Module {
                    private \stdClass $counter;
                    private Type $query;

                    public function setCounter(\stdClass $counter) : void
                    {
                        $this->counter = $counter;
                    }

                    public function setQuery(Type $type) : void
                    {
                        $this->query = $type;
                    }

                    public function processRequest(
                        Request $request,
                    ) : Request
                    {
                        ++$this->counter->count;

                        return $request;
                    }

                    public function processParsed(
                        ParsedRequest $request,
                    ) : ParsedRequest
                    {
                        $this->counter->count += 2;

                        return $request;
                    }

                    public function processNormalized(
                        NormalizedRequest $request,
                    ) : NormalizedRequest
                    {
                        $this->counter->count += 4;

                        return $request;
                    }

                    public function processFinalized(
                        FinalizedRequest $request,
                    ) : FinalizedRequest
                    {
                        $this->counter->count += 8;

                        return $request;
                    }

                    public function processResult(
                        Result $result,
                    ) : Result
                    {
                        $this->counter->count += 16;

                        return $result;
                    }
                },
                31,
            ],
            [
                new class implements Module {
                    private \stdClass $counter;
                    private Type $query;

                    public function setCounter(\stdClass $counter) : void
                    {
                        $this->counter = $counter;
                    }

                    public function setQuery(Type $type) : void
                    {
                        $this->query = $type;
                    }

                    public function processRequest(
                        Request $request,
                    ) : ParsedRequest
                    {
                        ++$this->counter->count;

                        return new ParsedRequest(
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
                        ParsedRequest $request,
                    ) : ParsedRequest
                    {
                        $this->counter->count += 2;

                        return $request;
                    }

                    public function processNormalized(
                        NormalizedRequest $request,
                    ) : NormalizedRequest
                    {
                        $this->counter->count += 4;

                        return $request;
                    }

                    public function processFinalized(
                        FinalizedRequest $request,
                    ) : FinalizedRequest
                    {
                        $this->counter->count += 8;

                        return $request;
                    }

                    public function processResult(
                        Result $result,
                    ) : Result
                    {
                        $this->counter->count += 16;

                        return $result;
                    }
                },
                29,
            ],
            [
                new class implements Module {
                    private \stdClass $counter;
                    private Type $query;

                    public function setCounter(\stdClass $counter) : void
                    {
                        $this->counter = $counter;
                    }

                    public function setQuery(Type $type) : void
                    {
                        $this->query = $type;
                    }

                    public function processRequest(
                        Request $request,
                    ) : Request
                    {
                        ++$this->counter->count;

                        return $request;
                    }

                    public function processParsed(
                        ParsedRequest $request,
                    ) : NormalizedRequest
                    {
                        $this->counter->count += 2;

                        return new NormalizedRequest(
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
                                            new DirectiveSet(),
                                        ),
                                    ]),
                                    new \Graphpinator\Normalizer\Variable\VariableSet(),
                                    new DirectiveSet(),
                                ),
                            ]),
                        );
                    }

                    public function processNormalized(
                        NormalizedRequest $request,
                    ) : NormalizedRequest
                    {
                        $this->counter->count += 4;

                        return $request;
                    }

                    public function processFinalized(
                        FinalizedRequest $request,
                    ) : FinalizedRequest
                    {
                        $this->counter->count += 8;

                        return $request;
                    }

                    public function processResult(
                        Result $result,
                    ) : Result
                    {
                        $this->counter->count += 16;

                        return $result;
                    }
                },
                27,
            ],
            [
                new class implements Module {
                    private \stdClass $counter;
                    private Type $query;

                    public function setCounter(\stdClass $counter) : void
                    {
                        $this->counter = $counter;
                    }

                    public function setQuery(Type $type) : void
                    {
                        $this->query = $type;
                    }

                    public function processRequest(
                        Request $request,
                    ) : Request
                    {
                        ++$this->counter->count;

                        return $request;
                    }

                    public function processParsed(
                        ParsedRequest $request,
                    ) : ParsedRequest
                    {
                        $this->counter->count += 2;

                        return $request;
                    }

                    public function processNormalized(
                        NormalizedRequest $request,
                    ) : FinalizedRequest
                    {
                        $this->counter->count += 4;

                        return new FinalizedRequest(
                            new \Graphpinator\Normalizer\Operation\Operation(
                                'query',
                                null,
                                $this->query,
                                new \Graphpinator\Normalizer\Selection\SelectionSet([
                                    new \Graphpinator\Normalizer\Selection\Field(
                                        $this->query->getFields()['field'],
                                        'field',
                                        new \Graphpinator\Value\ArgumentValueSet(),
                                        new DirectiveSet(),
                                    ),
                                ]),
                                new \Graphpinator\Normalizer\Variable\VariableSet(),
                                new DirectiveSet(),
                            ),
                        );
                    }

                    public function processFinalized(
                        FinalizedRequest $request,
                    ) : FinalizedRequest
                    {
                        $this->counter->count += 8;

                        return $request;
                    }

                    public function processResult(
                        Result $result,
                    ) : Result
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
    public function testSimple(Module $module, int $expectedCount) : void
    {
        $query = new class extends Type {
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
