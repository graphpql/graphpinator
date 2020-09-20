<?php

declare(strict_types = 1);

namespace Graphpinator\Argument\Constraint;

final class StringConstraint extends \Graphpinator\Argument\Constraint\LeafConstraint
{
    private ?int $minLength;
    private ?int $maxLength;
    private ?string $regex;
    private ?array $oneOf;

    public function __construct(?int $minLength = null, ?int $maxLength = null, ?string $regex = null, ?array $oneOf = null)
    {
        if ((\is_int($minLength) && $minLength < 0) ||
            (\is_int($maxLength) && $maxLength < 0)) {
            throw new \Graphpinator\Exception\Constraint\NegativeLengthParameter();
        }

        if (\is_array($oneOf)) {
            foreach ($oneOf as $item) {
                if (!\is_string($item)) {
                    throw new \Graphpinator\Exception\Constraint\InvalidOneOfParameter();
                }
            }
        }

        $this->minLength = $minLength;
        $this->maxLength = $maxLength;
        $this->regex = $regex;
        $this->oneOf = $oneOf;
    }

    public function print() : string
    {
        $components = [];

        if (\is_int($this->minLength)) {
            $components[] = 'minLength: ' . $this->minLength;
        }

        if (\is_int($this->maxLength)) {
            $components[] = 'maxLength: ' . $this->maxLength;
        }

        if (\is_string($this->regex)) {
            $components[] = 'regex: "' . $this->regex . '"';
        }

        if (\is_array($this->oneOf)) {
            $components[] = \count($this->oneOf) === 0
                ? 'oneOf: []'
                : 'oneOf: ["' . \implode('", "', $this->oneOf) . '"]';
        }

        return '@stringConstraint(' . \implode(', ', $components) . ')';
    }

    public function validateType(\Graphpinator\Type\Contract\Inputable $type) : bool
    {
        $namedType = $type->getNamedType();

        return $namedType instanceof \Graphpinator\Type\Scalar\StringType
            || $namedType instanceof \Graphpinator\Type\Scalar\IdType;
    }

    protected function validateFactoryMethod($inputValue) : void
    {
        \assert(\is_string($inputValue));

        if (\is_int($this->minLength) && \mb_strlen($inputValue) < $this->minLength) {
            throw new \Graphpinator\Exception\Constraint\MinLengthConstraintNotSatisfied();
        }

        if (\is_int($this->maxLength) && \mb_strlen($inputValue) > $this->maxLength) {
            throw new \Graphpinator\Exception\Constraint\MaxLengthConstraintNotSatisfied();
        }

        if (\is_string($this->regex) && \preg_match($this->regex, $inputValue) !== 1) {
            throw new \Graphpinator\Exception\Constraint\RegexConstraintNotSatisfied();
        }

        if (\is_array($this->oneOf) && !\in_array($inputValue, $this->oneOf, true)) {
            throw new \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied();
        }
    }
}
