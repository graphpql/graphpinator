<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Directive;

/**
 * @method \Graphpinator\Parser\Directive\Directive current() : object
 * @method \Graphpinator\Parser\Directive\Directive offsetGet($offset) : object
 */
final class DirectiveSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Directive::class;

    private string $location;

    public function __construct(array $data, string $location)
    {
        parent::__construct($data);

        $this->location = $location;
    }

    public function getLocation() : string
    {
        return $this->location;
    }

    public function normalize(
        \Graphpinator\Type\Contract\Definition $scopeType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    ) : \Graphpinator\Normalizer\Directive\DirectiveSet
    {
        return new \Graphpinator\Normalizer\Directive\DirectiveSet($this, $scopeType, $typeContainer, $variableSet);
    }
}
