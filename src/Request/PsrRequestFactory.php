<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class PsrRequestFactory implements \Graphpinator\Request\RequestFactory
{
    use \Nette\SmartObject;

    private \Psr\Http\Message\ServerRequestInterface $request;
    private bool $strict;

    public function __construct(\Psr\Http\Message\ServerRequestInterface $request, bool $strict = true)
    {
        $this->request = $request;
        $this->strict = $strict;
    }

    public function create() : Request
    {
        $method = $this->request->getMethod();

        if (!\in_array($method, ['GET', 'POST'], true)) {
            throw new \Graphpinator\Request\Exception\InvalidMethod();
        }

        $contentTypes = $this->request->getHeader('Content-Type');
        $contentType = \array_pop($contentTypes);

        if (\is_string($contentType) && \str_starts_with($contentType, 'multipart/form-data')) {
            if ($method === 'POST' && \array_key_exists('operations', $this->request->getParsedBody())) {
                return $this->applyJsonFactory(\Infinityloop\Utils\Json\MapJson::fromString($this->request->getParsedBody()['operations']));
            }

            throw new \Graphpinator\Request\Exception\InvalidMultipartRequest();
        }

        switch ($contentType) {
            case 'application/graphql':
                return new \Graphpinator\Request\Request($this->request->getBody()->getContents());
            case 'application/json':
                return $this->applyJsonFactory(\Infinityloop\Utils\Json\MapJson::fromString($this->request->getBody()->getContents()));
            default:
                $params = $this->request->getQueryParams();

                if (\array_key_exists('variables', $params)) {
                    $params['variables'] = \Infinityloop\Utils\Json\MapJson::fromString($params['variables'])->toNative();
                }

                return $this->applyJsonFactory(\Infinityloop\Utils\Json\MapJson::fromNative((object) $params));
        }
    }

    private function applyJsonFactory(\Infinityloop\Utils\Json\MapJson $json) : Request
    {
        $jsonFactory = new \Graphpinator\Request\JsonRequestFactory($json, $this->strict);

        return $jsonFactory->create();
    }
}
