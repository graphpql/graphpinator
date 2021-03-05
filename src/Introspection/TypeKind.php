<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

final class TypeKind extends \Graphpinator\Type\EnumType
{
    public const SCALAR = 'SCALAR';
    public const OBJECT = 'OBJECT';
    public const INTERFACE = 'INTERFACE';
    public const UNION = 'UNION';
    public const ENUM = 'ENUM';
    public const INPUT_OBJECT = 'INPUT_OBJECT';
    public const LIST = 'LIST';
    public const NON_NULL = 'NON_NULL';

    protected const NAME = '__TypeKind';
    protected const DESCRIPTION = 'Built-in introspection enum.';

    public function __construct()
    {
        parent::__construct(self::fromConstants());
    }
}
