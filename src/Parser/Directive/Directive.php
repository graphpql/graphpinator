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
    ) {
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
        \Graphpinator\Type\Container\Container $typeContainer
    ) : \Graphpinator\Normalizer\Directive\Directive
    {
        return new \Graphpinator\Normalizer\Directive\Directive(
            $typeContainer->getDirective($this->name),
            $this->arguments,
        );
    }
}
