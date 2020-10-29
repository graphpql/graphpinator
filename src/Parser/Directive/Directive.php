<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Directive;

final class Directive
{
    use \Nette\SmartObject;

    private string $name;
    private ?\Graphpinator\Parser\Value\NamedValueSet $arguments;

    public function __construct(
        string $name,
        ?\Graphpinator\Parser\Value\NamedValueSet $arguments
    )
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getArguments() : ?\Graphpinator\Parser\Value\NamedValueSet
    {
        return $this->arguments;
    }

    public function normalize(
        \Graphpinator\Container\Container $typeContainer
    ) : \Graphpinator\Normalizer\Directive\Directive
    {
        $directive = $typeContainer->getDirective($this->name);

        if (!$directive instanceof \Graphpinator\Directive\ExecutableDirective) {
            throw new \Graphpinator\Exception\Normalizer\DirectiveNotExecutable();
        }

        return new \Graphpinator\Normalizer\Directive\Directive(
            $directive,
            $this->arguments,
        );
    }
}
