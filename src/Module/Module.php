<?php

declare(strict_types = 1);

namespace Graphpinator\Module;

interface Module
{
    public function processRequest(\Graphpinator\Request\Request $request) :
    \Graphpinator\Request\Request|
    \Graphpinator\Parser\ParsedRequest|
    \Graphpinator\Normalizer\NormalizedRequest|
    \Graphpinator\OperationRequest;

    public function processParsed(\Graphpinator\Parser\ParsedRequest $request) :
    \Graphpinator\Parser\ParsedRequest|
    \Graphpinator\Normalizer\NormalizedRequest|
    \Graphpinator\OperationRequest;

    public function processNormalized(\Graphpinator\Normalizer\NormalizedRequest $request) :
    \Graphpinator\Normalizer\NormalizedRequest|
    \Graphpinator\OperationRequest;

    public function processFinalized(\Graphpinator\OperationRequest $request) : \Graphpinator\OperationRequest;
}
