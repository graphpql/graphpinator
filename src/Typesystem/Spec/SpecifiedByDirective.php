<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Spec;

use Graphpinator\Typesystem\Argument\Argument;
use Graphpinator\Typesystem\Argument\ArgumentSet;
use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\Container;
use Graphpinator\Typesystem\Directive;
use Graphpinator\Typesystem\Location\ScalarLocation;

#[Description('Built-in specifiedBy directive')]
final class SpecifiedByDirective extends Directive implements ScalarLocation
{
    protected const NAME = 'specifiedBy';

    #[\Override]
    protected function getFieldDefinition() : ArgumentSet
    {
        return new ArgumentSet([
            new Argument('url', Container::String()),
        ]);
    }
}
