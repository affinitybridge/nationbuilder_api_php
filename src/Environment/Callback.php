<?php

namespace Affinitybridge\NationBuilder\Environment;

use Affinitybridge\NationBuilder\Container as Container;

class Callback implements EnvironmentInterface {
    protected $container = null;
    protected $callbacks = [];

    public function __construct(Container $container)
    {
        $this->setContainer($container);
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function setCallback($key, $callback)
    {
        if (is_callable($callback)) {
            $this->callbacks[$key] = $callback;
        } else {
            throw new \Exception("Error: The callback given for '$key' is not really callable.");
        }
    }

    public function setCallbacks(array $callbacks)
    {
        foreach ($callbacks as $key => $callback) {
            $this->setCallback($key, $callback);
        }
    }

    public function credentialsLoad($connectionId)
    {
        return $this->callbacks['credentialsLoad']($connectionId);
    }

    public function httpCall($method, $url, array $body = null)
    {
        $headers = ['Accept' => ['application/json']];
        if (! is_null($body)) {
            $body = json_encode($body);
            $headers['Content-Type'] = ['application/json'];
        }
        $uri = $this->container->http_uri($url);
        $request = $this->container->http_request($method, $uri, $headers, $body);
        $container_in_closure = $this->container;
        $response = $this->callbacks['httpCall']($request, function ($statusCode, $reasonPhrase, $protocolVersion, $headers, $body) use ($container_in_closure) {
            return $container_in_closure->http_response($statusCode, $reasonPhrase, $protocolVersion, $headers, $body);
        });

        return [
            'statusCode' => $response->getStatusCode(),
            'reasonPhrase' => $response->getReasonPhrase(),
            'protocolVersion' => $response->getProtocolVersion(),
            'headers' => $response->getHeaders(),
            'body' => json_decode($response->getBody(), true),
        ];
    }
}
