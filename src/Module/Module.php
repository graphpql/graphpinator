<?php

declare(strict_types = 1);

namespace Graphpinator\Module;

use Graphpinator\Normalizer\FinalizedRequest;
use Graphpinator\Normalizer\NormalizedRequest;
use Graphpinator\Parser\ParsedRequest;
use Graphpinator\Request\Request;
use Graphpinator\Result;

interface Module
{
    public function processRequest(Request $request) : Request|ParsedRequest|NormalizedRequest|FinalizedRequest;

    public function processParsed(ParsedRequest $request) : ParsedRequest|NormalizedRequest|FinalizedRequest;

    public function processNormalized(NormalizedRequest $request) : NormalizedRequest|FinalizedRequest;

    public function processFinalized(FinalizedRequest $request) : FinalizedRequest;

    public function processResult(Result $result) : Result;
}
