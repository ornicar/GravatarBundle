<?php

namespace Ornicar\GravatarBundle;

use Doctrine\Common\Cache\Cache;

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
        $map = array(
            's' => $size    ?: $this->defaults['size'],
            'r' => $rating  ?: $this->defaults['rating'],
            'd' => $default ?: $this->defaults['default'],
        );

        $secure = $secure ?: $this->defaults['secure'];

        return ($secure ? 'https://secure' : 'http://www') . '.gravatar.com/avatar/' . $hash . '?' . http_build_query(array_filter($map));
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
}
