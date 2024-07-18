<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

final class DuplicateNonRepeatableDirective extends GraphpinatorBase
{
    public const MESSAGE = 'Duplicate non-repeatable directive in DirectiveUsageSet found.';
}
