<?php

namespace Ornicar\GravatarBundle;

use Doctrine\Common\Cache\Cache;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;
use Ornicar\GravatarBundle\Exception\ImageTransferException;
use Symfony\Component\Routing\RouterInterface;

/**
 * Simple wrapper to the gravatar API
 * http://en.gravatar.com/site/implement/url
 *
 * Usage:
 *      \Bundle\GravatarBundle\GravatarApi::getUrl('henrik@bearwoods.dk', 80, 'g', 'mm');
 *
 * @author     Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author     Henrik Bjørnskov <henrik@bearwoods.dk>
 */
class GravatarApi
{
    /**
     * @var array $defaults Array of default options that can be overriden with getters and in the construct.
     */
    protected $defaults = array(
        'size'    => 80,
        'rating'  => 'g',
        'default' => null,
        'secure'  => false,
    );

    /** @var Cache */
    private $cache;

    /** @var int */
    private $lifetime;

    /** @var RouterInterface */
    private $router;

    /** @var Client */
    private $client;

    /**
     * Constructor
     *
     * @param array $options the array is merged with the defaults.
     * @return void
     */
    public function __construct(array $options = array())
    {
        $this->defaults = array_merge($this->defaults, $options);
    }

    /**
     * Returns a url for a gravatar.
     *
     * @param  string  $email
     * @param  integer $size
     * @param  string  $rating
     * @param  string  $default
     * @param  Boolean $secure
     * @return string
     */
    public function getUrl($email, $size = null, $rating = null, $default = null, $secure = null)
    {
        $hash = md5(strtolower(trim($email)));

        return $this->getUrlForHash($hash, $size, $rating, $default, $secure);
    }

    /**
     * Returns a url for a gravatar for the given hash.
     *
     * @param  string  $hash
     * @param  integer $size
     * @param  string  $rating
     * @param  string  $default
     * @param  Boolean $secure
     * @return string
     */
    public function getUrlForHash($hash, $size = null, $rating = null, $default = null, $secure = null)
    {
        if ( $this->cache && $this->router ) {
            $url = $this->generateCachedUrl($hash, $size, $rating, $default, $secure);
        } else {
            $url = $this->generateGravatarServiceUrl($hash, $size, $rating, $default, $secure);
        }

        return $url;
    }

    /**
     * Checks if a gravatar exists for the email. It does this by checking for the presence of 404 in the header
     * returned. Will return null if fsockopen fails, for example when the hostname cannot be resolved.
     *
     * @param string $email
     * @return Boolean|null Boolean if we could connect, null if no connection to gravatar.com
     */
    public function exists($email)
    {
        $path = $this->getUrl($email, null, null, '404');

        if (!$sock = @fsockopen('gravatar.com', 80, $errorNo, $error)) {
            return null;
        }

        fputs($sock, "HEAD " . $path . " HTTP/1.0\r\n\r\n");
        $header = fgets($sock, 128);
        fclose($sock);
        return strpos($header, '404') ? false : true;
    }

    /**
     * @param Cache $cache
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param int $lifetime
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * The the URL to access the Gravatar Service
     *
     * @param $hash
     * @param $size
     * @param $rating
     * @param $default
     * @param $secure
     * @return string
     */
    private function generateGravatarServiceUrl($hash, $size, $rating, $default, $secure)
    {
        $map = array(
            's' => $size    ?: $this->defaults['size'],
            'r' => $rating  ?: $this->defaults['rating'],
            'd' => $default ?: $this->defaults['default'],
        );

        $url  = '';
        $url .= ($secure ? 'https://secure' : 'http://www') . '.gravatar.com/avatar/';
        $url .= $hash . '?';
        $url .= http_build_query(
            array_filter($map)
        );

        return $url;
    }

    /**
     * @param $hash
     * @param $size
     * @param $rating
     * @param $default
     * @param $secure
     * @return string
     */
    private function generateCachedUrl($hash, $size, $rating, $default, $secure)
    {
        if( $this->router === null ) {
            // Fallback?
            return $this->generateGravatarServiceUrl($hash, $size, $rating, $default, $secure);
        }

        return $this->router->generate(
            'ornicar_gravatar_image',
            array(
                'hash' => $hash,
                'size' => $size ?: $this->defaults['size'],
                'rating' => $rating ?: $this->defaults['rating'],
                'default' => $default ?: $this->defaults['default'],
                'secure' => $secure ? '1' : '0',
            )
        );
    }


    public function fetchImage($hash, $size, $rating, $default, $secure)
    {
        // Generate cache hash
        $cacheKey = sha1($hash . $size . $rating . $default . $secure);

        if ( $this->cache->contains($cacheKey) ) {
            return unserialize($this->cache->fetch($cacheKey));
        }

        $gravatarUrl = $this->generateGravatarServiceUrl($hash, $size, $rating, $default, $secure);
        $client = $this->getClient();

        try {
            /** @var Response $response */
            $response = $client->get($gravatarUrl);
        } catch (RequestException $e) {
            // We catch any exception
            throw new ImageTransferException($e->getMessage(), 0, $e);
        }

        $image = new GravatarImage(
            $response->getBody()->getContents(),
            $response->getBody()->getSize(),
            'image/jpeg'
        );

        $this->cache->save($cacheKey, serialize($image), $this->lifetime);

        return $image;
    }

    /**
     * TODO: We should inject this
     *
     * @return Client
     */
    public function getClient()
    {
        if ( $this->client instanceof Client ) {
            return $this->client;
        } else {
            $this->client = new Client();
            return $this->client;
        }
    }
}
