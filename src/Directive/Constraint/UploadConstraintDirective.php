<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

final class UploadConstraintDirective extends \Graphpinator\Directive\Directive
    implements \Graphpinator\Directive\Contract\TypeSystemDefinition
{
    protected const NAME = 'uploadConstraint';
    protected const DESCRIPTION = 'Graphpinator uploadConstraint directive.';

    public function __construct()
    {
        parent::__construct(
            [
                \Graphpinator\Directive\TypeSystemDirectiveLocation::ARGUMENT_DEFINITION,
            ],
            false,
            new \Graphpinator\Argument\ArgumentSet([
                new \Graphpinator\Argument\Argument('maxSize', \Graphpinator\Container\Container::Int()),
                new \Graphpinator\Argument\Argument('mimeType', \Graphpinator\Container\Container::String()->list()),
            ]),
        );
    }
}
