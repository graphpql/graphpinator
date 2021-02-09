<?php

declare(strict_types = 1);

namespace Graphpinator\Directive\Constraint;

interface ConstraintDirectiveAccessor
{
    public function getString() : StringConstraintDirective;

    public function getInt() : IntConstraintDirective;

    public function getFloat() : FloatConstraintDirective;

    public function getList() : ListConstraintDirective;

    public function getListInput() : ListConstraintInput;

    public function getObject() : ObjectConstraintDirective;
}
