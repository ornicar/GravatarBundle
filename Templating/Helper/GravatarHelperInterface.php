<?php

namespace Ornicar\GravatarBundle\Templating\Helper;

interface GravatarHelperInterface
{
    /**
     * Returns a url for a gravatar
     *
     * @param  string  $email
     * @param  integer $size
     * @param  string  $rating
     * @param  string  $default
     * @param  Boolean $secure
     * @return string
     */
    function getUrl($email, $size = null, $rating = null, $default = null, $secure = null);

    /**
     * Returns a url for a gravatar for a given hash
     *
     * @param  string  $hash
     * @param  integer $size
     * @param  string  $rating
     * @param  string  $default
     * @param  Boolean $secure
     * @return string
     */
    function getUrlForHash($hash, $size = null, $rating = null, $default = null, $secure = null);

    /**
     * Returns true if a avatar could be found for the email
     *
     * @param string $email
     * @return Boolean
     */
    function exists($email);
}
