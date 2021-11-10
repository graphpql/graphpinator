<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

use \Infinityloop\Utils\Json\MapJson;

final class PsrRequestFactory implements \Graphpinator\Request\RequestFactory
{
    use \Nette\SmartObject;

    public function __construct(
        private \Psr\Http\Message\ServerRequestInterface $request,
        private bool $strict = true,
    )
    {
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
                return $this->applyJsonFactory(MapJson::fromString($this->request->getParsedBody()['operations']));
            }

            throw new \Graphpinator\Request\Exception\InvalidMultipartRequest();
        }

        switch ($contentType) {
            case 'application/graphql':
                return new \Graphpinator\Request\Request($this->request->getBody()->getContents());
            case 'application/json':
                return $this->applyJsonFactory(MapJson::fromString($this->request->getBody()->getContents()));
            default:
                $params = $this->request->getQueryParams();

                if (\array_key_exists('variables', $params)) {
                    $params['variables'] = MapJson::fromString($params['variables'])->toNative();
                }

                return $this->applyJsonFactory(MapJson::fromNative((object) $params));
        }
    }

    private function applyJsonFactory(MapJson $json) : Request
    {
        $jsonFactory = new \Graphpinator\Request\JsonRequestFactory($json, $this->strict);

        return $jsonFactory->create();
    }
}
