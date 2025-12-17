<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Graphpinator;
use Graphpinator\Module\Module;
use Graphpinator\Module\ModuleSet;
use Graphpinator\Normalizer\Directive\DirectiveSet;
use Graphpinator\Normalizer\FinalizedRequest;
use Graphpinator\Normalizer\NormalizedRequest;
use Graphpinator\Normalizer\Operation\Operation as NormalizerOperation;
use Graphpinator\Normalizer\Operation\OperationSet as NormalizerOperationSet;
use Graphpinator\Normalizer\Selection\Field as NormalizerField;
use Graphpinator\Normalizer\Selection\SelectionSet;
use Graphpinator\Normalizer\Variable\VariableSet;
use Graphpinator\Parser\Field\Field;
use Graphpinator\Parser\Field\FieldSet;
use Graphpinator\Parser\Fragment\FragmentSet;
use Graphpinator\Parser\FragmentSpread\FragmentSpreadSet;
use Graphpinator\Parser\Operation\Operation;
use Graphpinator\Parser\Operation\OperationSet;
use Graphpinator\Parser\OperationType;
use Graphpinator\Parser\ParsedRequest;
use Graphpinator\Request\JsonRequestFactory;
use Graphpinator\Request\Request;
use Graphpinator\Resolver\Result;
use Graphpinator\SimpleContainer;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Field\ResolvableField;
use Graphpinator\Typesystem\Field\ResolvableFieldSet;
use Graphpinator\Typesystem\Schema;
use Graphpinator\Typesystem\Type;
use Graphpinator\Value\ArgumentValueSet;
use Infinityloop\Utils\Json;
use PHPUnit\Framework\TestCase;

final class ModuleTest extends TestCase
{
    public static function moduleDataProvider() : array
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
                            new OperationSet([
                                new Operation(
                                    OperationType::QUERY,
                                    new FieldSet(
                                        [new Field('field')],
                                        new FragmentSpreadSet(),
                                    ),
                                ),
                            ]),
                            new FragmentSet(),
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
                            new NormalizerOperationSet([
                                new NormalizerOperation(
                                    OperationType::QUERY,
                                    null,
                                    $this->query,
                                    new SelectionSet([
                                        new NormalizerField(
                                            $this->query->getFields()['field'],
                                            'field',
                                            new ArgumentValueSet(),
                                            new DirectiveSet(),
                                        ),
                                    ]),
                                    new VariableSet(),
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
                            new NormalizerOperation(
                                OperationType::QUERY,
                                null,
                                $this->query,
                                new SelectionSet([
                                    new NormalizerField(
                                        $this->query->getFields()['field'],
                                        'field',
                                        new ArgumentValueSet(),
                                        new DirectiveSet(),
                                    ),
                                ]),
                                new VariableSet(),
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

            protected function getFieldDefinition() : ResolvableFieldSet
            {
                return new ResolvableFieldSet([
                    ResolvableField::create(
                        'field',
                        Container::String()->notNull(),
                        static function ($parent) : string {
                            return 'test';
                        },
                    ),
                ]);
            }
        };
        $container = new SimpleContainer([$query], []);
        $schema = new Schema($container, $query);

        $counter = new \stdClass();
        $counter->count = 0;
        $module->setCounter($counter);
        $module->setQuery($query);

        $graphpinator = new Graphpinator($schema, false, new ModuleSet([$module]));
        $result = $graphpinator->run(new JsonRequestFactory(Json::fromNative((object) [
             'query' => '{ field }',
        ])));
        self::assertSame(
            Json::fromNative((object) ['data' => ['field' => 'test']])->toString(),
            $result->toString(),
        );
        self::assertSame($expectedCount, $counter->count);
    }
}
