<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Directive;

final class ExpectedListValue extends \Graphpinator\Exception\Directive\BaseWhereException
{
    public const MESSAGE = 'The specified numeric index "%d" is only usable for a list, got %s.';

    public function __construct(int $currentWhere, string $got)
    {
        $this->messageArgs = [$currentWhere, $got];

        parent::__construct();
    }
}
