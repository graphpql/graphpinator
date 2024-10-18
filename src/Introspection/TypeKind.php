<?php

declare(strict_types = 1);

namespace Graphpinator\Introspection;

use Graphpinator\Typesystem\Attribute\Description;
use Graphpinator\Typesystem\EnumType;

#[Description('Built-in introspection type')]
final class TypeKind extends EnumType
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

    public function __construct()
    {
        parent::__construct(self::fromConstants());
    }
}
