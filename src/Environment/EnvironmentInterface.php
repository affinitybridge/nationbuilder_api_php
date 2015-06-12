<?php

namespace Affinitybridge\NationBuilder\Environment;

use Affinitybridge\NationBuilder\Container as Container;

interface EnvironmentInterface {
    public function __construct(Container $container);
    public function setContainer(Container $container);
    public function credentialsLoad($connectionId);
    public function httpCall($method, $url, array $data = null);
}
