<?php

namespace Affinitybridge\NationBuilder\Endpoint;

use Affinitybridge\NationBuilder\Container as Container;
use Affinitybridge\NationBuilder\Connection\ConnectionInterface as ConnectionInterface;
use Affinitybridge\NationBuilder\Validator as Validator;
use Affinitybridge\NationBuilder\Exception\ApiErrorException as ApiErrorException;
use Affinitybridge\NationBuilder\Exception\UnexpectedResponseStructureException as UnexpectedResponseStructureException;

abstract class EndpointAbstract {
    protected $container = null;
    protected $environment = null;
    protected $connection = null;

    public function __construct(Container $container, ConnectionInterface $connection) {
        $this->container = $container;
        $this->connection = $connection;
        $this->environment = $this->container->environment;
    }

    protected function apiGet($urlPath, array $allowedQueryParameters = [], array $queryParameters = []) {
        return $this->apiCall('GET', $urlPath, [], null, $allowedQueryParameters, $queryParameters);
    }

    protected function apiPut($urlPath, array $allowedBodyParameters = [], array $bodyParameters = null, array $allowedQueryParameters = [], array $queryParameters = []) {
        return $this->apiCall('PUT', $urlPath, $allowedBodyParameters, $bodyParameters, $allowedQueryParameters, $queryParameters);
    }

    protected function apiPost($urlPath, array $allowedBodyParameters = [], array $bodyParameters = null, array $allowedQueryParameters = [], array $queryParameters = []) {
        return $this->apiCall('POST', $urlPath, $allowedBodyParameters, $bodyParameters, $allowedQueryParameters, $queryParameters);
    }

    protected function apiDelete($urlPath, array $allowedBodyParameters = [], array $bodyParameters = null, array $allowedQueryParameters = [], array $queryParameters = []) {
        return $this->apiCall('DELETE', $urlPath, $allowedBodyParameters, $bodyParameters, $allowedQueryParameters, $queryParameters);
    }

    protected function apiCall($method, $urlPath, array $allowedBodyParameters = [], array $bodyParameters = null, array $allowedQueryParameters = [], array $queryParameters = []) {
        if (! is_null($bodyParameters)) {
            $bodyParameters = Validator::normalize($bodyParameters, $allowedBodyParameters);
            $bodyParameters = Validator::inlineJsonPointer($bodyParameters);
        }

        $queryParameters = Validator::normalize($queryParameters, $allowedQueryParameters);
        $queryParameters = Validator::inlineJsonPointer($queryParameters);

        $url = $this->connection->makeApiUrl($urlPath, $queryParameters);
        $response = $this->container->environment->httpCall($method, $url, $bodyParameters);
        // TODO: Record rate-limit headers. Save them into the connection object.
        // {
        //     "nation-ratelimit-limit": "10000",
        //     "nation-ratelimit-remaining": "9946",
        //     "nation-ratelimit-reset": "1455580800",
        //     "x-ratelimit-limit": "10s",
        //     "x-ratelimit-remaining": "99",
        //     "x-ratelimit-reset": "1455578571"
        // }
        return $response;
    }
}
