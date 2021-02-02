<?php

declare(strict_types = 1);

namespace Graphpinator\Type\Enum;

final class EnumItem implements \Graphpinator\Printable\Printable
{
    use \Nette\SmartObject;
    use \Graphpinator\Utils\TOptionalDescription;
    use \Graphpinator\Directive\THasDirectives;
    use \Graphpinator\Directive\TDeprecatable;

    private string $name;

    public function __construct(string $name, ?string $description = null)
    {
        $this->name = $name;
        $this->description = $description;
        $this->directives = new \Graphpinator\Directive\DirectiveUsageSet();
        $this->directiveLocation = \Graphpinator\Directive\TypeSystemDirectiveLocation::ENUM_VALUE;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function printSchema(int $indentLevel) : string
    {
        return $this->printDescription($indentLevel) . $this->getName() . $this->printDirectives();
    }
}
