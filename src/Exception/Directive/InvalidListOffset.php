<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Directive;

final class InvalidListOffset extends \Graphpinator\Exception\Directive\BaseWhereException
{
    public const MESSAGE = 'The specified numeric index "%d" is out of range.';

    public function __construct(int $currentWhere)
    {
        $this->messageArgs = [$currentWhere];

        parent::__construct();
    }
}
