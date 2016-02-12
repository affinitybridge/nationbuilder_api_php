<?php

namespace Affinitybridge\NationBuilder\Http;

use Affinitybridge\NationBuilder\Container as Container;

/**
 * Selected PSR-7 Response methods. Only the bare minimum which is needed here.
 *
 * @see http://www.php-fig.org/psr/psr-7/
 *
 * This is to stay compatible with PSR-7 while not requiring the presence of a
 * full-blown PSR-7 implementation.
 */

/*

use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\StreamInterface as StreamInterface;
use Psr\Http\Message\UriInterface as UriInterface;

// These are the ResponseInterface methods:

public function getStatusCode();
public function withStatus($code, $reasonPhrase = '');
public function getReasonPhrase();

// These are the MessageInterface methods:

public function getProtocolVersion();
public function withProtocolVersion($version);
public function getHeaders();
public function hasHeader($name);
public function getHeader($name);
public function getHeaderLine($name);
public function withHeader($name, $value);
public function withAddedHeader($name, $value);
public function withoutHeader($name);
public function getBody();
public function withBody(StreamInterface $body);

*/

class Response
{
    protected $container = null;
    protected $statusCode = '';
    protected $reasonPhrase = '';
    protected $protocolVersion = '';
    protected $headers = '';
    protected $bodyString = '';

    static public function hasCompatibleMethodSubset($response)
    {
        return
            method_exists($response, 'getStatusCode')
            &&
            method_exists($response, 'getReasonPhrase')
            &&
            method_exists($response, 'getProtocolVersion')
            &&
            method_exists($response, 'getHeaders')
            &&
            method_exists($response, 'getBody')
        ;
    }

    public function __construct(Container $container, $statusCode, $reasonPhrase, $protocolVersion, array $headers, $bodyString)
    {
        $this->setContainer($container);
        $this->statusCode = $statusCode;
        $this->reasonPhrase = $reasonPhrase;
        $this->protocolVersion = $protocolVersion;
        $this->headers = $headers;
        $this->bodyString = $bodyString;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->bodyString;
    }
}
