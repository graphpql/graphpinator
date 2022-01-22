<?php

declare(strict_types = 1);

namespace Graphpinator\Typesystem\Argument;

final class Argument implements \Graphpinator\Typesystem\Contract\Component
{
    use \Nette\SmartObject;
    use \Graphpinator\Typesystem\Utils\TOptionalDescription;
    use \Graphpinator\Typesystem\Utils\THasDirectives;
    use \Graphpinator\Typesystem\Utils\TDeprecatable;

    private ?\Graphpinator\Value\ArgumentValue $defaultValue = null;

    public function __construct(
        private string $name,
        private \Graphpinator\Typesystem\Contract\Inputable $type,
    )
    {
        $this->directiveUsages = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsageSet();
    }

    public static function create(string $name, \Graphpinator\Typesystem\Contract\Inputable $type) : self
    {
        return new self($name, $type);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Graphpinator\Typesystem\Contract\Inputable
    {
        return $this->type;
    }

    public function getDefaultValue() : ?\Graphpinator\Value\ArgumentValue
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(mixed $defaultValue) : self
    {
        $this->defaultValue = new \Graphpinator\Value\ArgumentValue(
            $this,
            $this->getType()->accept(new \Graphpinator\Value\ConvertRawValueVisitor($defaultValue, new \Graphpinator\Common\Path())),
            false,
        );

        return $this;
    }

    public function accept(\Graphpinator\Typesystem\Contract\ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitArgument($this);
    }

    public function addDirective(
        \Graphpinator\Typesystem\Location\ArgumentDefinitionLocation $directive,
        array $arguments = [],
    ) : self
    {
        $usage = new \Graphpinator\Typesystem\DirectiveUsage\DirectiveUsage($directive, $arguments);

        if (\Graphpinator\Graphpinator::$validateSchema && !$directive->validateArgumentUsage($this, $usage->getArgumentValues())) {
            throw new \Graphpinator\Typesystem\Exception\DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }
}
