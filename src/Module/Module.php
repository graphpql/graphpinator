<?php

declare(strict_types = 1);

namespace Graphpinator\Module;

interface Module
{
    public function processRequest(\Graphpinator\Request\Request $request) :
    \Graphpinator\Request\Request|
    \Graphpinator\Parser\ParsedRequest|
    \Graphpinator\Normalizer\NormalizedRequest|
    \Graphpinator\Normalizer\FinalizedRequest;

    public function processParsed(\Graphpinator\Parser\ParsedRequest $request) :
    \Graphpinator\Parser\ParsedRequest|
    \Graphpinator\Normalizer\NormalizedRequest|
    \Graphpinator\Normalizer\FinalizedRequest;

    public function processNormalized(\Graphpinator\Normalizer\NormalizedRequest $request) :
    \Graphpinator\Normalizer\NormalizedRequest|
    \Graphpinator\Normalizer\FinalizedRequest;

    public function processFinalized(\Graphpinator\Normalizer\FinalizedRequest $request) : \Graphpinator\Normalizer\FinalizedRequest;
}
