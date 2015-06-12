<?php

namespace Affinitybridge\NationBuilder\Endpoint;

use Affinitybridge\NationBuilder\Container as Container;
use Affinitybridge\NationBuilder\Connection\ConnectionInterface as ConnectionInterface;
use Affinitybridge\NationBuilder\Validator as Validator;

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
        $queryParameters = Validator::normalize($queryParameters, $allowedQueryParameters);
        $queryParameters = Validator::inlineJsonPointer($queryParameters);
        $url = $this->connection->makeApiUrl($urlPath, $queryParameters);
        return $this->container->environment->httpCall('GET', $url);
    }

    protected function apiPut($urlPath, array $allowedBodyParameters = [], array $bodyParameters = [], array $allowedQueryParameters = [], array $queryParameters = []) {
        $bodyParameters = Validator::normalize($bodyParameters, $allowedBodyParameters);
        $bodyParameters = Validator::inlineJsonPointer($bodyParameters);

        $queryParameters = Validator::normalize($queryParameters, $allowedQueryParameters);
        $queryParameters = Validator::inlineJsonPointer($queryParameters);

        $url = $this->connection->makeApiUrl($urlPath, $queryParameters);
        return $this->container->environment->httpCall('PUT', $url, $bodyParameters);
    }

    protected function apiPost($urlPath, array $allowedBodyParameters = [], array $bodyParameters = [], array $allowedQueryParameters = [], array $queryParameters = []) {
        $bodyParameters = Validator::normalize($bodyParameters, $allowedBodyParameters);
        $bodyParameters = Validator::inlineJsonPointer($bodyParameters);

        $queryParameters = Validator::normalize($queryParameters, $allowedQueryParameters);
        $queryParameters = Validator::inlineJsonPointer($queryParameters);

        $url = $this->connection->makeApiUrl($urlPath, $queryParameters);
        return $this->container->environment->httpCall('POST', $url, $bodyParameters);
    }
}
