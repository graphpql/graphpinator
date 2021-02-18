<?php

declare(strict_types = 1);

namespace Graphpinator\Directive;

trait THasDirectives
{
    protected \Graphpinator\Directive\DirectiveUsageSet $directiveUsages;

    public function getDirectiveUsages() : \Graphpinator\Directive\DirectiveUsageSet
    {
        return $this->directiveUsages;
    }
}
