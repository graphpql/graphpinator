<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class StringConstraint extends \Graphpinator\Constraint\LeafConstraint
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

    public function validateType(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        $namedType = $type->getNamedType();

        return $namedType instanceof \Graphpinator\Type\Scalar\StringType
            || $namedType instanceof \Graphpinator\Type\Scalar\IdType;
    }

    protected function validateFactoryMethod(\stdClass|array|string|int|float|bool|null $rawValue) : void
    {
        \assert(\is_string($rawValue));

        if (\is_int($this->minLength) && \mb_strlen($rawValue) < $this->minLength) {
            throw new \Graphpinator\Exception\Constraint\MinLengthConstraintNotSatisfied();
        }

        if (\is_int($this->maxLength) && \mb_strlen($rawValue) > $this->maxLength) {
            throw new \Graphpinator\Exception\Constraint\MaxLengthConstraintNotSatisfied();
        }

        if (\is_string($this->regex) && \preg_match($this->regex, $rawValue) !== 1) {
            throw new \Graphpinator\Exception\Constraint\RegexConstraintNotSatisfied();
        }

        if (\is_array($this->oneOf) && !\in_array($rawValue, $this->oneOf, true)) {
            throw new \Graphpinator\Exception\Constraint\OneOfConstraintNotSatisfied();
        }
    }

    protected function isGreaterSet(
        \Graphpinator\Constraint\ArgumentFieldConstraint $greater,
        \Graphpinator\Constraint\ArgumentFieldConstraint $smaller
    ) : bool
    {
        \assert($greater instanceof self);
        \assert($smaller instanceof self);

        if (\is_int($greater->minLength) && ($smaller->minLength === null || $smaller->minLength < $greater->minLength)) {
            return false;
        }

        if (\is_int($greater->maxLength) && ($smaller->maxLength === null || $smaller->maxLength > $greater->maxLength)) {
            return false;
        }

        if (\is_string($greater->regex) && ($smaller->regex === null || $smaller->regex !== $greater->regex)) {
            return false;
        }

        return !\is_array($greater->oneOf) || ($smaller->oneOf !== null && self::validateOneOf($greater->oneOf, $smaller->oneOf));
    }
}
