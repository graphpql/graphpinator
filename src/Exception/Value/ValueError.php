<?php

declare(strict_types = 1);

namespace Graphpinator\Exception\Value;

abstract class ValueError extends \Graphpinator\Exception\GraphpinatorBase
{
    protected bool $outputable;

    public function __construct(bool $outputable)
    {
        parent::__construct();

        $this->outputable = $outputable;
    }

    public function isOutputable() : bool
    {
        return $this->outputable;
    }
}
