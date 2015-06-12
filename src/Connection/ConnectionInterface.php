<?php

namespace Affinitybridge\NationBuilder\Connection;

use Affinitybridge\NationBuilder\Container as Container;

interface ConnectionInterface {
    public function __construct(Container $container, $apiBaseUrl, $accessToken);
    public function makeApiUrl($apiPath, array $queryParameters = []);
    public function people();
}
