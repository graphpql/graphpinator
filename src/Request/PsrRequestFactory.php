<?php

declare(strict_types = 1);

namespace Graphpinator\Request;

final class PsrRequestFactory implements RequestFactory
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
            throw new \Graphpinator\Exception\Request\InvalidMethod();
        }

        $contentTypes = $this->request->getHeader('Content-Type');
        $contentType = \array_pop($contentTypes);

        if (\is_string($contentType) && \str_starts_with($contentType, 'multipart/form-data')) {
            if ($method === 'POST' && \array_key_exists('operations', $this->request->getParsedBody())) {
                return $this->applyJsonFactory(\Graphpinator\Json::fromString($this->request->getParsedBody()['operations']));
            }

            throw new \Graphpinator\Exception\Request\InvalidMultipartRequest();
        }

        switch ($contentType) {
            case 'application/graphql':
                return new Request($this->request->getBody()->getContents());
            case 'application/json':
                return $this->applyJsonFactory(\Graphpinator\Json::fromString($this->request->getBody()->getContents()));
            default:
                $params = $this->request->getQueryParams();

                if (\array_key_exists('variables', $params)) {
                    $params['variables'] = \Graphpinator\Json::fromString($params['variables'])->toObject();
                }

                return $this->applyJsonFactory(\Graphpinator\Json::fromObject((object) $params));
        }
    }

    private function applyJsonFactory(\Graphpinator\Json $json) : Request
    {
        $jsonFactory = new JsonRequestFactory($json, $this->strict);

        return $jsonFactory->create();
    }
}
