<?php

declare(strict_types = 1);

namespace Graphpinator\Exception;

abstract class GraphpinatorBase extends \Exception implements \JsonSerializable
{
    public const MESSAGE = '';

    protected ?\Graphpinator\Source\Location $location = null;
    protected ?Path $path = null;
    protected ?array $extensions = null;

    public function __construct(?\Graphpinator\Source\Location $location = null, ?Path $path = null, ?array $extensions = null)
    {
        parent::__construct(static::MESSAGE);

        $this->location = $location;
        $this->path = $path;
        $this->extensions = $extensions;
    }

    final public function jsonSerialize() : array
    {
        if (!$this->isOutputable()) {
            return self::notOutputableResponse();
        }

        $result = [
            'message' => $this->getMessage(),
        ];

        if ($this->location instanceof \Graphpinator\Source\Location) {
            $result['locations'] = [$this->location];
        }

        if ($this->path instanceof Path) {
            $result['path'] = $this->path;
        }

        if (\is_array($this->extensions)) {
            $result['extensions'] = $this->extensions;
        }

        return $result;
    }

    public static function notOutputableResponse() : array
    {
        return [
            'message' => 'Server responded with unknown error.'
        ];
    }

    protected function isOutputable() : bool
    {
        return false;
    }
}
