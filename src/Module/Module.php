<?php

declare(strict_types = 1);

namespace Graphpinator\Module;

interface Module
{
    public function process(\Graphpinator\ParsedRequest $request) : \Graphpinator\ParsedRequest;
}
