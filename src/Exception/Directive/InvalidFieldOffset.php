<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Directive;

final class InvalidFieldOffset extends \Graphpinator\Exception\Directive\BaseWhereException
{
    public const MESSAGE = 'The specified Field "%s" doesnt exist in Type "%s"';

    public function __construct(string $currentWhere, string $type)
    {
        $this->messageArgs = [$currentWhere, $type];

        parent::__construct();
    }
}
