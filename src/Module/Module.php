<?php

declare(strict_types = 1);

namespace Graphpinator\Module;

use Graphpinator\Parser\ParsedRequest;
use \Graphpinator\Normalizer\NormalizedRequest;
use \Graphpinator\Normalizer\FinalizedRequest;

interface Module
{
    public function processRequest(\Graphpinator\Request\Request $request) : \Graphpinator\Request\Request|ParsedRequest|NormalizedRequest|FinalizedRequest;

    public function processParsed(ParsedRequest $request) : ParsedRequest|NormalizedRequest|FinalizedRequest;

    public function processNormalized(NormalizedRequest $request) : NormalizedRequest|FinalizedRequest;

    public function processFinalized(FinalizedRequest $request) : FinalizedRequest;

    public function processResult(\Graphpinator\Result $result) : \Graphpinator\Result;
}
