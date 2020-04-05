<?php

declare(strict_types = 1);

namespace Graphpinator\Tokenizer;

final class OperationType
{
    use \Nette\StaticClass;

    public const QUERY = 'query';
    public const MUTATION = 'mutation';
    public const SUBSCRIPTION = 'subscription';

    public const KEYWORDS = [
        self::QUERY => true,
        self::MUTATION => true,
        self::SUBSCRIPTION => true,
    ];

    public static function isOperationKeyword(string $name) : bool
    {
        return \array_key_exists(\strtolower($name), self::KEYWORDS);
    }
}
