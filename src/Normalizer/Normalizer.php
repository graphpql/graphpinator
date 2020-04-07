<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class Normalizer
{
    use \Nette\SmartObject;

    private \Graphpinator\Type\Resolver $resolver;

    public function __construct(\Graphpinator\Type\Resolver $resolver)
    {
        $this->resolver = $resolver;
    }

    public function normalize(\Graphpinator\Parser\ParseResult $parseResult) : Operation
    {
        return $parseResult->normalize($this->resolver);
    }
}
