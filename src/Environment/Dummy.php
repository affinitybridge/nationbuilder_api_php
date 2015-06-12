<?php

namespace Affinitybridge\NationBuilder\Environment;

use Affinitybridge\NationBuilder\Container as Container;

class Dummy implements EnvironmentInterface {
    public function __construct(Container $container)
    {
    }

    public function setContainer(Container $container)
    {
    }

    public function credentialsLoad($connectionId)
    {
        return [
            'api_base_url' => '',
            'access_token' => '',
        ];
    }

    public function httpCall($method, $url, array $data = null)
    {
        return [];
    }
}
