<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Spec;

final class SpecifiedByDirective extends \Graphpinator\Directive\Directive
    implements \Graphpinator\Directive\Contract\ScalarLocation
{
    protected const NAME = 'specified';
    protected const DESCRIPTION = 'Built-in specified by directive.';

    protected function getFieldDefinition() : \Graphpinator\Argument\ArgumentSet
    {
        return new \Graphpinator\Argument\ArgumentSet([
            new \Graphpinator\Argument\Argument('by', \Graphpinator\Container\Container::String()),
        ]);
    }
}
