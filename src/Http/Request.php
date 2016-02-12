<?php

namespace Affinitybridge\NationBuilder\Http;

use Affinitybridge\NationBuilder\Container as Container;

/**
 * Selected PSR-7 Request methods. Only the bare minimum which is needed here.
 *
 * @see http://www.php-fig.org/psr/psr-7/
 *
 * This is to stay compatible with PSR-7 while not requiring the presence of a
 * full-blown PSR-7 implementation.
 */

/*

use Psr\Http\Message\RequestInterface as RequestInterface;
use Psr\Http\Message\StreamInterface as StreamInterface;
use Psr\Http\Message\UriInterface as UriInterface;

// These are the RequestInterface methods:

public function getRequestTarget();
public function withRequestTarget($requestTarget);
public function getMethod();
public function withMethod($method);
public function getUri();
public function withUri(UriInterface $uri, $preserveHost = false);

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

class Request
{
    protected $container = null;
    protected $method = '';
    protected $uri = null;
    protected $headers = '';
    protected $body = '';

    static public function hasCompatibleMethodSubset($request)
    {
        return
            method_exists($request, 'getMethod')
            &&
            method_exists($request, 'getUri')
            &&
            method_exists($request, 'getHeaders')
            &&
            method_exists($request, 'getBody')
        ;
    }

    public function __construct(Container $container, $method, $uri, array $headers, $body)
    {
        if (! Uri::hasCompatibleMethodSubset($uri)) {
            throw new \Exception("Error: Parameter `uri` is not a compatible object.");
        }
        $this->setContainer($container);
        $this->method = (string) $method;
        $this->uri = $uri;
        $this->headers = (array) $headers;
        $this->body = (string) $body;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getBody()
    {
        return $this->body;
    }
}
