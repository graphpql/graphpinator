<?php

declare(strict_types = 1);

namespace Graphpinator\Parser\Directive;

final class Directive
{
    use \Nette\SmartObject;

    private string $name;
    private ?\Graphpinator\Parser\Value\ArgumentValueSet $arguments;

    public function __construct(
        string $name,
        ?\Graphpinator\Parser\Value\ArgumentValueSet $arguments
    )
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getArguments() : ?\Graphpinator\Parser\Value\ArgumentValueSet
    {
        return $this->arguments;
    }

    public function normalize(
        \Graphpinator\Container\Container $typeContainer
    ) : \Graphpinator\Normalizer\Directive\Directive
    {
        return new \Graphpinator\Normalizer\Directive\Directive(
            $this,
            $typeContainer,
        );
    }
}
