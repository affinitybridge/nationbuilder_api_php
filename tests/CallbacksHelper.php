<?php

namespace Affinitybridge\NationBuilder\Tests;

use Affinitybridge\NationBuilder\Environment\Callback as CallbackEnvironment;
use Affinitybridge\NationBuilder\Container as Container;
use Affinitybridge\NationBuilder\Api as Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class CallbacksHelper {
    protected $credentials = [];
    public function __construct($credentials) {
        $this->credentials = $credentials;
    }

    public function credentialsLoad($connectionId) {
        return $this->credentials;
    }

    public function httpCall($request, $makeResponseCallback) {
        $guzzleClient = new GuzzleClient();
        $guzzleRequest = new GuzzleRequest($request->getMethod(), (string) $request->getUri(), $request->getHeaders(), $request->getBody());
        $guzzleResponse = $guzzleClient->send($guzzleRequest);
        return $makeResponseCallback(
            $guzzleResponse->getStatusCode(),
            $guzzleResponse->getReasonPhrase(),
            $guzzleResponse->getProtocolVersion(),
            $guzzleResponse->getHeaders(),
            $guzzleResponse->getBody()
        );
    }
}
