<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Directive;

final class ExpectedTypeValue extends \Graphpinator\Exception\Directive\DirectiveError
{
    public const MESSAGE = 'Expected type value, got %s.';

    public function __construct(string $className)
    {
        $this->messageArgs = [$className];

        parent::__construct();
    }
}
