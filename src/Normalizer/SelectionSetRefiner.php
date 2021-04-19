<?php

declare(strict_types = 1);

namespace Graphpinator\Normalizer;

final class SelectionSetRefiner
{
    use \Nette\SmartObject;

    private array $fieldsForName = [];
    private array $visitedFragments = [];
    private \SplStack $scopeStack;
    private array $modules;

    public function __construct(
        private \Graphpinator\Normalizer\Selection\SelectionSet $selections,
        private \Graphpinator\Type\Contract\Outputable $scope,
    )
    {
        $this->modules = [
            new \Graphpinator\Normalizer\RefinerModule\DuplicateFieldModule($this->selections),
            new \Graphpinator\Normalizer\RefinerModule\DuplicateFragmentSpreadModule($this->selections),
        ];
    }

    public function refine() : \Graphpinator\Normalizer\Selection\SelectionSet
    {
        foreach ($this->modules as $module) {
            $module->refine();
        }

        return $this->selections;
    }

    public function visitField(\Graphpinator\Normalizer\Selection\Field $field) : ?\Graphpinator\Normalizer\Selection\Field
    {
        if (!\array_key_exists($field->getOutputName(), $this->fieldsForName)) {
            $this->fieldsForName[$field->getOutputName()] = [$field];

            return $field;
        }

        $fieldArguments = $field->getArguments();
        $fieldDirectives = $field->getDirectives();
        $fieldReturnType = $field->getField()->getType();

        foreach ($this->fieldsForName[$field->getOutputName()] as $conflict) {
            \assert($conflict instanceof \Graphpinator\Normalizer\Selection\Field);

            $conflictReturnType = $conflict->getField()->getType();

            /** Fields must have same response shape (return type) */
            if (!$fieldReturnType->isInstanceOf($conflictReturnType) ||
                !$conflictReturnType->isInstanceOf($fieldReturnType)) {
                throw new \Graphpinator\Normalizer\Exception\ConflictingFieldType();
            }

            /** Fields have type conditions which can never occur together */
            if (!self::canOccurTogether($this->scopeStack->top(), $this->scope)) {
                continue;
            }

            /** Fields have same alias, but refer to different field */
            if ($field->getName() !== $conflict->getName()) {
                throw new \Graphpinator\Normalizer\Exception\ConflictingFieldAlias();
            }

            /** Fields have different arguments */
            if (!$fieldArguments->isSame($conflict->getArguments())) {
                throw new \Graphpinator\Normalizer\Exception\ConflictingFieldArguments();
            }

            /** Fields have different directives */
            if (!$fieldDirectives->isSame($conflict->getDirectives())) {
                throw new \Graphpinator\Normalizer\Exception\ConflictingFieldDirectives();
            }

            /** Fields are composite -> combine their fields */
            if ($field->getSelections() instanceof \Graphpinator\Normalizer\Selection\SelectionSet) {
                $mergedSet = $field->getSelections()->merge($conflict->getSelections());
                $refiner = new self($mergedSet, $field->getField()->getType());

                $conflict->setSelections($refiner->refine());
            }

            /** Found identical conflict, we can safely exclude it */
            return null;
        }

        $this->fieldsForName[$field->getOutputName()][] = $field;

        return $field;
    }

    private static function canOccurTogether(\Graphpinator\Type\Contract\Outputable $typeA, \Graphpinator\Type\Contract\Outputable $typeB) : bool
    {
        return $typeA->isInstanceOf($typeB) // one is instance of other
            || $typeB->isInstanceOf($typeA)
            || !($typeA instanceof \Graphpinator\Type\Type) // one is not object type
            || !($typeB instanceof \Graphpinator\Type\Type);
    }
}
