<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Directive;

final class ExpectedTypeValue extends \Graphpinator\Exception\Directive\BaseWhereException
{
    public const MESSAGE = 'The specified Field "%s" doesnt exist in Type "%s".';

    public function __construct(string $currentWhere, \Graphpinator\Value\ResolvedValue $resolvedValue)
    {
        $this->messageArgs = [$currentWhere, $this->printType($resolvedValue)];

        parent::__construct();
    }
}
