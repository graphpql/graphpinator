<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

trait THasDirectives
{
    protected \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet $directiveUsages;

    public function getDirectiveUsages() : \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet
    {
        return $this->directiveUsages;
    }
}
