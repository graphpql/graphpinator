<?php

declare(strict_types = 1);

namespace Graphpinator\Constraint;

final class UploadConstraint extends \Graphpinator\Constraint\LeafConstraint
{
    public function __construct(private ?int $maxSize = null, private ?array $mimeType = null)
    {

    }

    public function print() : string
    {
        $components = [];

        if (\is_int($this->maxSize)) {
            $components[] = 'maxSize: ' . $this->maxSize;
        }

        if (\is_array($this->mimeType)) {
            $components[] = \count($this->mimeType) === 0
                ? 'mimeType: []'
                : 'mimeType: ["' . \implode('", "', $this->mimeType) . '"]';
        }

        return '@uploadConstraint(' . \implode(', ', $components) . ')';
    }

    public function validateType(\Graphpinator\Type\Contract\Definition $type) : bool
    {
        return $type->getNamedType() instanceof \Graphpinator\Module\Upload\UploadType;
    }

    protected function validateFactoryMethod(\stdClass|array|string|int|float|bool|null|\Psr\Http\Message\UploadedFileInterface $rawValue) : void
    {
        \assert($rawValue instanceof \Psr\Http\Message\UploadedFileInterface);

        if (\is_int($this->maxSize) && $rawValue->getSize() > $this->maxSize) {
            throw new \Graphpinator\Exception\Constraint\MaxSizeConstraintNotSatisfied();
        }

        if (\is_array($this->mimeType) && !\in_array(\mime_content_type($rawValue->getStream()->getMetadata('uri')), $this->mimeType, true)) {
            throw new \Graphpinator\Exception\Constraint\MimeTypeConstraintNotSatisfied();
        }
    }

    protected function isGreaterSet(
        \Graphpinator\Constraint\ArgumentFieldConstraint $greater,
        \Graphpinator\Constraint\ArgumentFieldConstraint $smaller
    ) : bool
    {
        \assert($greater instanceof self);
        \assert($smaller instanceof self);

        if (\is_int($greater->maxSize) && ($smaller->maxSize === null || $smaller->maxSize > $greater->maxSize)) {
            return false;
        }

        return !\is_array($greater->mimeType) || ($smaller->mimeType !== null && self::validateOneOf($greater->mimeType, $smaller->mimeType));
    }
}
