<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

final class SpecifiedByDirective extends \Graphpinator\Typesystem\Directive implements \Graphpinator\Typesystem\Location\ScalarLocation
{
    protected const NAME = 'specifiedBy';
    protected const DESCRIPTION = 'Built-in specified by directive.';

    protected function getFieldDefinition() : \Graphpinator\Typesystem\Argument\ArgumentSet
    {
        return new \Graphpinator\Typesystem\Argument\ArgumentSet([
            new \Graphpinator\Typesystem\Argument\Argument('url', \Graphpinator\Typesystem\Container::String()),
        ]);
    }
}
