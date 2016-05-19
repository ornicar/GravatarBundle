<?php

namespace Ornicar\GravatarBundle\Api;

/**
 * Simple wrapper to the gravatar API
 * http://en.gravatar.com/site/implement/url.
 *
 * Usage:
 *      \Bundle\GravatarBundle\GravatarApi::getUrl('henrik@bearwoods.dk', 80, 'g', 'mm');
 *
 * @author     Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author     Henrik Bjørnskov <henrik@bearwoods.dk>
 */
class GravatarClient implements GravatarClientInterface
{
    /**
     * @var array Array of default options that can be overriden with getters and in the construct.
     */
    protected $defaults = array(
        'size'    => 80,
        'rating'  => 'g',
        'default' => null,
        'secure'  => false,
    );

    /**
     * Constructor.
     *
     * @param array $options the array is merged with the defaults.
     */
    public function __construct(array $options = array())
    {
        $this->defaults = array_merge($this->defaults, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl($email, $size = null, $rating = null, $default = null, $secure = null)
    {
        $hash = md5(strtolower(trim($email)));

        return $this->getUrlForHash($hash, $size, $rating, $default, $secure);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrlForHash($hash, $size = null, $rating = null, $default = null, $secure = null)
    {
        $map = array(
            's' => $size    ?: $this->defaults['size'],
            'r' => $rating  ?: $this->defaults['rating'],
            'd' => $default ?: $this->defaults['default'],
        );

        $secure = $secure ?: $this->defaults['secure'];

        return ($secure ? 'https://secure' : 'http://www').'.gravatar.com/avatar/'.$hash.'?'.http_build_query(array_filter($map));
    }

    /**
     * {@inheritDoc}
     */
    public function getProfileUrl($email, $secure = null)
    {
        $hash = md5(strtolower(trim($email)));

        return $this->getProfileUrlForHash($hash, $secure);
    }

    /**
     * {@inheritDoc}
     */
    public function getProfileUrlForHash($hash, $secure = null)
    {
        $secure = $secure ?: $this->defaults['secure'];

        return ($secure ? 'https://secure' : 'http://www').'.gravatar.com/'.$hash;
    }

    /**
     * {@inheritDoc}
     */
    public function exists($email)
    {
        $path = $this->getUrl($email, null, null, '404');

        if (!$sock = @fsockopen('gravatar.com', 80, $errorNo, $error)) {
            return;
        }

        fputs($sock, 'HEAD '.$path." HTTP/1.0\r\n\r\n");
        $header = fgets($sock, 128);
        fclose($sock);

        return strpos($header, '404') ? false : true;
    }
}
