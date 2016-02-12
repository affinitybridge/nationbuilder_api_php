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

use Psr\Http\Message\UriInterface as UriInterface;

// These are the UriInterface methods:

public function getScheme();
public function getAuthority();
public function getUserInfo();
public function getHost();
public function getPort();
public function getPath();
public function getQuery();
public function getFragment();
public function withScheme($scheme);
public function withUserInfo($user, $password = null);
public function withHost($host);
public function withPort($port);
public function withPath($path);
public function withQuery($query);
public function withFragment($fragment);
public function __toString();

*/

class Uri
{
    protected $container = null;
    protected $uriString = '';

    static public function hasCompatibleMethodSubset($uri)
    {
        return
            method_exists($uri, '__toString')
        ;
    }

    public function __construct(Container $container, $uriString)
    {
        $this->setContainer($container);
        $this->uriString = $uriString;
    }

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    public function __toString()
    {
        return $this->uriString;
    }
}
