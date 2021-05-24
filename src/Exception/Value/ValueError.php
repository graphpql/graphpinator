<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Value;

abstract class ValueError extends \Graphpinator\Exception\GraphpinatorBase
{
    public function __construct(protected bool $outputable)
    {
        parent::__construct();
    }

    public function isOutputable() : bool
    {
        return $this->outputable;
    }
}
