<?php

namespace Ornicar\GravatarBundle\Api;

use Ornicar\GravatarBundle\Cache\CacheInterface;

/**
 * Cached gravatar API client
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class CachedGravatarClient implements GravatarClientInterface
{
    /**
     * @var GravatarClientInterface
     */
    private $api;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * Constructor.
     *
     * @param GravatarClientInterface $api
     * @param CacheInterface          $cache
     */
    public function __construct(GravatarClientInterface $api, CacheInterface $cache)
    {
        $this->api = $api;
        $this->cache = $cache;
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl($email, $size = null, $rating = null, $default = null, $secure = null)
    {
        return $this->api->getUrl($email, $size, $rating, $default, $secure);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrlForHash($hash, $size = null, $rating = null, $default = null, $secure = null)
    {
        return $this->api->getUrlForHash($hash, $size, $rating, $default, $secure);
    }

    /**
     * {@inheritDoc}
     */
    public function getProfileUrl($email, $secure = null)
    {
        return $this->api->getProfileUrl($email, $secure);
    }

    /**
     * {@inheritDoc}
     */
    public function getProfileUrlForHash($hash, $secure = null)
    {
        return $this->getProfileUrlForHash($hash, $secure);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($email)
    {
        if ($this->cache->has($email)) {
            return $this->cache->get($email);
        }

        $exist = $this->api->exists($email);

        $this->cache->set($email, $exist);

        return $exist;
    }
}
