<?php

declare(strict_types = 1);

namespace Infinityloop\Graphpinator\Tokenizer;

final class TokenType
{
    use \Nette\StaticClass;

    public const NEWLINE = 'newline';
    public const COMMENT = '#';
    public const COMMA = ',';
    # lexical
    public const OPERATION = 'operation';
    public const FRAGMENT = 'fragment';
    public const NAME = 'name';
    public const VARIABLE = 'var';
    public const INT = 'int';
    public const FLOAT = 'float';
    public const STRING = 'string';
    public const NULL = 'null';
    public const TRUE = 'true';
    public const FALSE = 'false';
    # punctators
    public const AMP = '&';
    public const PIPE = '|';
    public const EXCL = '!';
    public const PAR_O = '(';
    public const PAR_C = ')';
    public const CUR_O = '{';
    public const CUR_C = '}';
    public const SQU_O = '[';
    public const SQU_C = ']';
    public const ELLIP = '...';
    public const COLON = ':';
    public const EQUAL = '=';
    public const AT = '@';

    public const IGNORABLE = [
        self::COMMA => true,
        self::COMMENT => true,
        self::NEWLINE => true,
    ];
}
