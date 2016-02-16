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
        return $this->apiCall('GET', $urlPath, [], [], $allowedQueryParameters, $queryParameters);
    }

    protected function apiPut($urlPath, array $allowedBodyParameters = [], array $bodyParameters = [], array $allowedQueryParameters = [], array $queryParameters = []) {
        return $this->apiCall('PUT', $urlPath, $allowedBodyParameters, $bodyParameters, $allowedQueryParameters, $queryParameters);
    }

    protected function apiPost($urlPath, array $allowedBodyParameters = [], array $bodyParameters = [], array $allowedQueryParameters = [], array $queryParameters = []) {
        return $this->apiCall('POST', $urlPath, $allowedBodyParameters, $bodyParameters, $allowedQueryParameters, $queryParameters);
    }

    protected function apiDelete($urlPath, array $allowedBodyParameters = [], array $bodyParameters = [], array $allowedQueryParameters = [], array $queryParameters = []) {
        return $this->apiCall('DELETE', $urlPath, $allowedBodyParameters, $bodyParameters, $allowedQueryParameters, $queryParameters);
    }

    protected function apiCall($method, $urlPath, array $allowedBodyParameters = [], array $bodyParameters = [], array $allowedQueryParameters = [], array $queryParameters = []) {
        $bodyParameters = Validator::normalize($bodyParameters, $allowedBodyParameters);
        $bodyParameters = Validator::inlineJsonPointer($bodyParameters);

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

    public function throwIfError($response) {
        if (
            (400 <= $response['statusCode'])
            ||
            (
                isset($response['body']['code'], $response['body']['message'])
                &&
                (1 === preg_match('{error|not_found|bad_request|not_acceptable}i', $response['body']['code']))
            )
        ) {
            // examples:
            //
            // 400
            // {
            //     "code": "bad_request",
            //     "message": "Bad Request."
            // }
            //
            // 400
            // {
            //     "code": "missing_parameters",
            //     "message": "Missing Parameters.",
            //     "parameters": [
            //         "location: lat, lng required"
            //     ]
            // }
            //
            // 400
            // {
            //     "code": "missing_parameters",
            //     "message": "Missing Parameters.",
            //     "parameters": [
            //         "person"
            //     ]
            // }
            //
            // 404
            // {
            //     "code": "not_found",
            //     "message": "Record not found"
            // }
            //
            // 406
            // {
            //     "code": "not_acceptable",
            //     "message": "I can only talk JSON. Please set 'Accept' and 'Content-Type' to 'application/json' in your http request header."
            // }
            //
            // 500
            // {
            //     "code": "server_error",
            //     "message": "You have encountered a server error."
            // }
            throw new \Exception('NationBuilder API returned an error: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
        }
    }
}
