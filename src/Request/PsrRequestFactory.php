<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

use \Infinityloop\Utils\Json;

final class PsrRequestFactory implements RequestFactory
{
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
                return $this->applyJsonFactory(Json::fromString($this->request->getParsedBody()['operations']));
            }

            throw new \Graphpinator\Request\Exception\InvalidMultipartRequest();
        }

        switch ($contentType) {
            case 'application/graphql':
                return new Request($this->request->getBody()->getContents());
            case 'application/json':
                return $this->applyJsonFactory(Json::fromString($this->request->getBody()->getContents()));
            default:
                $params = $this->request->getQueryParams();

                if (\array_key_exists('variables', $params)) {
                    $params['variables'] = Json::fromString($params['variables'])->toNative();
                }

                return $this->applyJsonFactory(Json::fromNative((object) $params));
        }
    }

    private function applyJsonFactory(Json $json) : Request
    {
        return (new JsonRequestFactory($json, $this->strict))->create();
    }
}
