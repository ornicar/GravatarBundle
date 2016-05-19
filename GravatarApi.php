<?php

namespace Ornicar\GravatarBundle;

use Ornicar\GravatarBundle\Api\GravatarClient;
use Ornicar\GravatarBundle\Api\GravatarClientInterface;

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
class GravatarApi
{
    /**
     * @var GravatarClientInterface
     */
    private $client;

    /**
     * Constructor.
     *
     * @param array                   $options For BC
     * @param GravatarClientInterface $client
     */
    public function __construct(array $options = array(), GravatarClientInterface $client = null)
    {
        if (!$client) {
            $client = new GravatarClient($client);
        }

        $this->client = $client;
    }

    /**
     * Returns a url for a gravatar.
     *
     * @param string  $email
     * @param int     $size
     * @param string  $rating
     * @param string  $default
     * @param Boolean $secure
     *
     * @return string
     */
    public function getUrl($email, $size = null, $rating = null, $default = null, $secure = null)
    {
        return $this->client->getUrl($email, $size, $rating, $default, $secure);
    }

    /**
     * Returns a url for a gravatar for the given hash.
     *
     * @param string  $hash
     * @param int     $size
     * @param string  $rating
     * @param string  $default
     * @param Boolean $secure
     *
     * @return string
     */
    public function getUrlForHash($hash, $size = null, $rating = null, $default = null, $secure = null)
    {
        return $this->client->getUrlForHash($hash, $size, $rating, $default, $secure);
    }

    /**
     * Returns a url for a gravatar profile.
     *
     * @param string  $email
     * @param Boolean $secure
     *
     * @return string
     */
    public function getProfileUrl($email, $secure = null)
    {
        return $this->client->getProfileUrl($email, $secure);
    }

    /**
     * Returns a url for a gravatar profile for the given hash.
     *
     * @param string  $hash
     * @param Boolean $secure
     *
     * @return string
     */
    public function getProfileUrlForHash($hash, $secure = null)
    {
        return $this->client->getProfileUrlForHash($hash, $secure);
    }

    /**
     * Checks if a gravatar exists for the email. It does this by checking for the presence of 404 in the header
     * returned. Will return null if fsockopen fails, for example when the hostname cannot be resolved.
     *
     * @param string $email
     *
     * @return Boolean|null Boolean if we could connect, null if no connection to gravatar.com
     */
    public function exists($email)
    {
        return $this->client->exists($email);
    }
}
