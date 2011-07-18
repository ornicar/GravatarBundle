<?php

namespace Bundle\GravatarBundle\Twig;

use Bundle\GravatarBundle\GravatarApi;

/**
 * @author Thibault Duplessis
 * @author Henrik Bjornskov   <hb@peytz.dk>
 */
class GravatarExtension extends \Twig_Extension
{
    /**
     * @var GravatarApi $api
     */
    protected $api;

    /**
     * @param GravatarApi $api
     */
    public function __construct(GravatarApi $api)
    {
        $this->api = $api;
    }

    public function getFunctions()
    {
        return array(
            'gravatar'          => new \Twig_Function_Method($this, 'getUrl'),
            'gravatar_hash'     => new \Twig_Function_Method($this, 'getUrlForHash'),
            'gravatar_exists'   => new \Twig_Function_Method($this, 'exists'),
        );
    }

    public function getUrl($email, $size = null, $rating = null, $default = null)
    {
        return $this->api->getUrl($email, $size, $rating, $default);
    }

    public function getUrlForHash($hash, $size = null, $rating = null, $default = null)
    {
        return $this->api->getUrlForHash($hash, $size, $rating, $default);
    }

    public function exists($email)
    {
        return $this->api->exists($email);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gravatar';
    }
}
