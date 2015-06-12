<?php

namespace Affinitybridge\NationBuilder;

class Api
{
    protected $container = null;

    public function __construct(array $injectionOverrides = [])
    {
        $injectionDefaults = [
            'environment' => 'Affinitybridge\\NationBuilder\\Environment\\Dummy',
            'connection' => 'Affinitybridge\\NationBuilder\\Connection',
            // 'validator' => 'Affinitybridge\\NationBuilder\\Validator',
            'endpoint_people' => 'Affinitybridge\\NationBuilder\\Endpoint\\People',
        ];
        $this->container = new Container(array_merge($injectionDefaults, $injectionOverrides));
    }

    public function connect($connectionId)
    {
        $credentials = $this->container->environment->credentialsLoad($connectionId);
        return $this->container->connection($credentials['api_base_url'], $credentials['access_token']);
    }
}
