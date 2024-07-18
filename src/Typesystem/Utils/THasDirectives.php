<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Utils;

use Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet;

trait THasDirectives
{
    protected DirectiveUsageSet $directiveUsages;

    public function getDirectiveUsages() : DirectiveUsageSet
    {
        return $this->directiveUsages;
    }
}
