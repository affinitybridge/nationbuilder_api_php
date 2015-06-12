<?php

namespace Affinitybridge\NationBuilder\Environment;

use Affinitybridge\NationBuilder\Container as Container;

class Callback implements EnvironmentInterface {
    protected $container = null;
    protected $callbacks = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function setCallbacks(array $callbacks)
    {
        if (
            is_callable($callbacks['credentialsLoad'])
            &&
            is_callable($callbacks['httpCall'])
        ) {
            $this->callbacks = $callbacks;
        } else {
            throw new \Exception("Error: One or more given callbacks are not really callable.");
        }
    }

    public function credentialsLoad($connectionId)
    {
        return $this->callbacks['credentialsLoad']($connectionId);
    }

    public function httpCall($method, $url, array $body = null)
    {
        $headers = ['Accept' => 'application/json'];
        if (! is_null($body)) {
            $body = json_encode($body);
            $headers['Content-Type'] = 'application/json';
        }
        $result = $this->callbacks['httpCall']($method, $url, $headers, $body);
         // TODO Also relay HTTP result code and maybe result headers.
        return json_decode($result['body'], true);
    }
}
