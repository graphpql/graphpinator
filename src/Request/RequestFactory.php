<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

interface RequestFactory
{
    public function create() : Request;
}
