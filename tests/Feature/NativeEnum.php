<?php

declare(strict_types = 1);

namespace Graphpinator\Tests\Feature;

use Graphpinator\Typesystem\Attribute\Description;

enum NativeEnum : string
{
    #[Description('Some description for following enum case')]
    case ABC = 'ABC';
    #[Description('Another description for following enum case')]
    case XYZ = 'XYZ';
}
