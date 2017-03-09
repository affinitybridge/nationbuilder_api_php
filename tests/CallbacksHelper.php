<?php

namespace Affinitybridge\NationBuilder\Tests;

use Affinitybridge\NationBuilder\Environment\Callback as CallbackEnvironment;
use Affinitybridge\NationBuilder\Container as Container;
use Affinitybridge\NationBuilder\Api as Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\ClientException as ClientException;

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
        try {
            $guzzleResponse = $guzzleClient->send($guzzleRequest);
        }
        catch (ClientException $e) {
            // @todo print this only if the --verbose flag is set.
            $response = $e->getResponse();
            print PHP_EOL . PHP_EOL . "CAUGHT ClientException" . PHP_EOL;
            print PHP_EOL . PHP_EOL . "REQUEST METHOD:" . PHP_EOL . PHP_EOL;
            print $request->getMethod();
            print PHP_EOL . PHP_EOL . "REQUEST URL:" . PHP_EOL . PHP_EOL;
            print (string) $request->getUri();
            print PHP_EOL . PHP_EOL . "REQUEST HEADERS:" . PHP_EOL . PHP_EOL;
            print var_export($request->getHeaders(), true);
            print PHP_EOL . PHP_EOL . "REQUEST BODY:" . PHP_EOL . PHP_EOL;
            print $request->getBody();
            print PHP_EOL . PHP_EOL . "STATUS CODE:" . PHP_EOL . PHP_EOL;
            print $response->getStatusCode();
            print PHP_EOL . PHP_EOL . "REASON PHRASE:" . PHP_EOL . PHP_EOL;
            print $response->getReasonPhrase();
            print PHP_EOL . PHP_EOL . "PROTOCOL VERSION:" . PHP_EOL . PHP_EOL;
            print $response->getProtocolVersion();
            print PHP_EOL . PHP_EOL . "RESPONSE HEADERS:" . PHP_EOL . PHP_EOL;
            print var_export($response->getHeaders(), true);
            print PHP_EOL . PHP_EOL . "RESPONSE BODY:" . PHP_EOL . PHP_EOL;
            print $response->getBody();
            throw $e;
        }
        return $makeResponseCallback(
            $guzzleResponse->getStatusCode(),
            $guzzleResponse->getReasonPhrase(),
            $guzzleResponse->getProtocolVersion(),
            $guzzleResponse->getHeaders(),
            $guzzleResponse->getBody()
        );
    }
}
