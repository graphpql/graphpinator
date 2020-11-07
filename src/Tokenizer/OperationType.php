<?php

declare(strict_types = 1);

namespace Graphpinator\Tokenizer;

final class OperationType
{
    use \Nette\StaticClass;

    public const QUERY = 'query';
    public const MUTATION = 'mutation';
    public const SUBSCRIPTION = 'subscription';
}
