<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer\Directive;

/**
 * @method \Graphpinator\Normalizer\Directive\Directive current() : object
 * @method \Graphpinator\Normalizer\Directive\Directive offsetGet($offset) : object
 */
final class DirectiveSet extends \Infinityloop\Utils\ObjectSet
{
    protected const INNER_CLASS = Directive::class;

    public function __construct(
        \Graphpinator\Parser\Directive\DirectiveSet $parsed,
        \Graphpinator\Type\Contract\Definition $scopeType,
        \Graphpinator\Container\Container $typeContainer,
        \Graphpinator\Normalizer\Variable\VariableSet $variableSet,
    )
    {
        parent::__construct();

        $directiveTypes = [];

        foreach ($parsed as $parsedDirective) {
            $normalizedDirective = new Directive($parsedDirective, $scopeType, $typeContainer, $variableSet);
            $directive = $normalizedDirective->getDirective();

            if (!$directive->isRepeatable()) {
                if (\array_key_exists($directive->getName(), $directiveTypes)) {
                    throw new \Graphpinator\Exception\Normalizer\DuplicatedDirective();
                }

                $directiveTypes[$directive->getName()] = true;
            }

            $this[] = $normalizedDirective;
        }
    }

    public function applyVariables(\Graphpinator\Normalizer\VariableValueSet $variables) : void
    {
        foreach ($this as $directive) {
            $directive->applyVariables($variables);
        }
    }
}
