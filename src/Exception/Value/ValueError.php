<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Value;

abstract class ValueError extends \Graphpinator\Exception\GraphpinatorBase
{
    public function __construct(
        protected bool $outputable,
        array $messageArgs = [],
    )
    {
        parent::__construct($messageArgs);
    }

    public function isOutputable() : bool
    {
        return $this->outputable;
    }
}
