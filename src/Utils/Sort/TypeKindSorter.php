<?php

declare(strict_types = 1);

namespace Graphpinator\Utils\Sort;

class TypeKindSorter implements PrintSorter
{
    public function sortTypes(array $types) : array
    {
        $interface = $union = $input = $enum = $scalar = $type = [];

        foreach ($types as $name => $type) {
            switch ($type->getTypeKind()) {
                case \Graphpinator\Type\Introspection\TypeKind::INTERFACE:
                    $interface[$name] = $type;
                    break;
                case \Graphpinator\Type\Introspection\TypeKind::UNION:
                    $union[$name] = $type;
                    break;
                case \Graphpinator\Type\Introspection\TypeKind::INPUT_OBJECT:
                    $input[$name] = $type;
                    break;
                case \Graphpinator\Type\Introspection\TypeKind::ENUM:
                    $enum[$name] = $type;
                    break;
                case \Graphpinator\Type\Introspection\TypeKind::SCALAR:
                    $scalar[$name] = $type;
                    break;
                case \Graphpinator\Type\Introspection\TypeKind::OBJECT:
                    $type[$name] = $type;
                    break;
            }
        }

        \ksort($interface);
        \ksort($union);
        \ksort($input);
        \ksort($enum);
        \ksort($scalar);
        \ksort($type);

        return \array_merge($interface, $type, $union, $input, $scalar, $enum);
    }

    public function sortDirectives(array $directives) : array
    {
        \ksort($directives);

        return $directives;
    }
}
