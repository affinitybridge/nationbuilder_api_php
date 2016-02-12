<?php 

namespace Affinitybridge\NationBuilder;

use Affinitybridge\NationBuilder\Connection\ConnectionInterface as ConnectionInterface;

class Connection implements ConnectionInterface {
    protected $container = null;
    protected $apiBaseUrl = '';
    protected $accessToken = '';

    public function __construct(Container $container, $apiBaseUrl, $accessToken) {
        $this->container = $container;
        $this->apiBaseUrl = trim($apiBaseUrl, '/') . '/';
        $this->accessToken = $accessToken;
    }

    public function makeApiUrl($apiPath, array $queryParameters = []) {
        $query = '?' . http_build_query($queryParameters + ['access_token' => $this->accessToken]);
        return $this->apiBaseUrl . $apiPath . $query;
    }

    public function people() {
        return $this->container->endpoint_people($this);
    }

    public function tags() {
        return $this->container->endpoint_tags($this);
    }
}
