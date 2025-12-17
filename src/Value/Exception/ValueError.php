<?php

declare(strict_types = 1);

namespace Graphpinator\Value\Exception;

use Graphpinator\Exception\GraphpinatorBase;

abstract class ValueError extends GraphpinatorBase
{
    public function __construct(
        protected bool $outputable,
        array $messageArgs = [],
    )
    {
        parent::__construct($messageArgs);
    }

    #[\Override]
    public function isOutputable() : bool
    {
        return $this->outputable;
    }
}
