<?php

namespace Ornicar\GravatarBundle\Templating\Helper;

use Doctrine\Common\Cache\Cache;
use Ornicar\GravatarBundle\GravatarApi;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Templating\Helper\Helper;

/**
 * Symfony 2 Helper for Gravatar. Uses Bundle\GravatarBundle\GravatarApi.
 *
 * @author Thibault Duplessis
 * @author Henrik Bjornskov <henrik@bearwoods.dk>
 */
class GravatarHelper extends Helper implements GravatarHelperInterface
{
    /**
     * @var Ornicar\GravatarBundle\GravatarApi
     */
    protected $api;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @var int
     */
    protected $ttl = 0;

    /**
     * Constructor.
     *
     * @param GravatarApi          $api
     * @param RouterInterface|null $router
     * @param Cache|null           $cache
     * @param int                  $ttl
     */
    public function __construct(GravatarApi $api, RouterInterface $router = null, Cache $cache = null, $ttl = 0)
    {
        $this->api = $api;
        $this->router = $router;
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($email, $size = null, $rating = null, $default = null, $secure = true)
    {
        $key = 'url_'.$email.$size.$rating;
        if (null !== $cachedData = $this->getCachedData($key)) {
            return $cachedData;
        }
        $url = $this->api->getUrl($email, $size, $rating, $default, $this->isSecure($secure));
        $this->setCachedData($key, $url);

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlForHash($hash, $size = null, $rating = null, $default = null, $secure = true)
    {
        $key = 'url_hash_'.$hash.$size.$rating;
        if (null !== $cachedData = $this->getCachedData($key)) {
            return $cachedData;
        }
        $url = $this->api->getUrlForHash($hash, $size, $rating, $default, $this->isSecure($secure));
        $this->setCachedData($key, $url);

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfileUrl($email, $secure = true)
    {
        $key = 'profile_'.$email;
        if (null !== $cachedData = $this->getCachedData($key)) {
            return $cachedData;
        }
        $url = $this->api->getProfileUrl($email, $this->isSecure($secure));
        $this->setCachedData($key, $url);

        return $url;
    }

    /**
     * {@inheritdoc}
     */
    public function getProfileUrlForHash($hash, $secure = true)
    {
        $key = 'profile_url_hash_'.$hash;
        if (null !== $cachedData = $this->getCachedData($key)) {
            return $cachedData;
        }
        $url = $this->api->getProfileUrlForHash($hash, $this->isSecure($secure));
        $this->setCachedData($key, $url);

        return $url;
    }

    public function render($email, array $options = array())
    {
        $size = isset($options['size']) ? $options['size'] : null;
        $rating = isset($options['rating']) ? $options['rating'] : null;
        $default = isset($options['default']) ? $options['default'] : null;
        $secure = $this->isSecure();

        return $this->getUrl($email, $size, $rating, $default, $secure);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($email)
    {
        $key = 'exists_'.$email;
        if (null !== $cachedData = $this->getCachedData($key)) {
            return $cachedData;
        }
        $exists = $this->api->exists($email);
        $this->setCachedData($key, $exists);

        return $exists;
    }

    /**
     * Name of this Helper.
     *
     * @return string
     */
    public function getName()
    {
        return 'gravatar';
    }

    /**
     * Returns true if avatar should be fetched over secure connection.
     *
     * @param mixed $preset
     *
     * @return bool
     */
    protected function isSecure($preset = true)
    {
        if (null !== $preset) {
            return (bool) $preset;
        }

        if (null === $this->router) {
            return false;
        }

        return 'https' == strtolower($this->router->getContext()->getScheme());
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    protected function getCachedData($key)
    {
        if (null === $this->cache) {
            return null;
        }
        $cachedData = $this->cache->fetch($key);
        if ($cachedData !== false) {
            return $cachedData;
        }
    }

    /**
     * @param string $key
     * @param mixed  $data
     */
    protected function setCachedData($key, $data)
    {
        if (null !== $this->cache) {
            $this->cache->save($key, $data, $this->ttl);
        }
    }
}
