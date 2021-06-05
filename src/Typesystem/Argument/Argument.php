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
        private \Graphpinator\Type\Contract\Inputable $type,
    )
    {
        $this->directiveUsages = new \Graphpinator\DirectiveUsage\DirectiveUsageSet();
    }

    public static function create(string $name, \Graphpinator\Type\Contract\Inputable $type) : self
    {
        return new self($name, $type);
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : \Graphpinator\Type\Contract\Inputable
    {
        return $this->type;
    }

    public function getDefaultValue() : ?\Graphpinator\Value\ArgumentValue
    {
        return $this->defaultValue;
    }

    public function setDefaultValue(\stdClass|array|string|int|float|bool|null $defaultValue) : self
    {
        $this->defaultValue = \Graphpinator\Value\ConvertRawValueVisitor::convertArgument(
            $this,
            $defaultValue,
            new \Graphpinator\Common\Path(),
        );

        return $this;
    }

    public function accept(\Graphpinator\Typesystem\Contract\ComponentVisitor $visitor) : mixed
    {
        return $visitor->visitArgument($this);
    }

    public function addDirective(
        \Graphpinator\Directive\Contract\ArgumentDefinitionLocation $directive,
        array $arguments = [],
    ) : self
    {
        $usage = new \Graphpinator\DirectiveUsage\DirectiveUsage($directive, $arguments);

        if (\Graphpinator\Graphpinator::$validateSchema && !$directive->validateArgumentUsage($this, $usage->getArgumentValues())) {
            throw new \Graphpinator\Exception\Type\DirectiveIncorrectType();
        }

        $this->directiveUsages[] = $usage;

        return $this;
    }
}
