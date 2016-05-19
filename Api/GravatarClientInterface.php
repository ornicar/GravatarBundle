<?php

namespace Ornicar\GravatarBundle\Api;

/**
 * All Gravatar API should implement this interface
 * http://en.gravatar.com/site/implement/url.
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface GravatarClientInterface
{
    /**
     * Returns a url for a gravatar.
     *
     * @param string $email
     * @param int    $size
     * @param string $rating
     * @param string $default
     * @param bool   $secure
     *
     * @return string
     */
    public function getUrl($email, $size = null, $rating = null, $default = null, $secure = null);

    /**
     * Returns a url for a gravatar for the given hash.
     *
     * @param string $hash
     * @param int    $size
     * @param string $rating
     * @param string $default
     * @param bool   $secure
     *
     * @return string
     */
    public function getUrlForHash($hash, $size = null, $rating = null, $default = null, $secure = null);

    /**
     * Returns a url for a gravatar profile.
     *
     * @param string $email
     * @param bool   $secure
     *
     * @return string
     */
    public function getProfileUrl($email, $secure = null);

    /**
     * Returns a url for a gravatar profile for the given hash.
     *
     * @param string  $hash
     * @param Boolean $secure
     *
     * @return string
     */
    public function getProfileUrlForHash($hash, $secure = null);

    /**
     * Checks if a gravatar exists for the email. It does this by checking for the presence of 404 in the header
     * returned. Will return null if fsockopen fails, for example when the hostname cannot be resolved.
     *
     * @param string $email
     *
     * @return bool|null Boolean if we could connect, null if no connection to gravatar.com
     */
    public function exists($email);
}
