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

    /**
     * @return array
     */
    public function getGlobals()
    {
        return array(
            'fn_gravatar' => new \Twig_Function($this->api, 'getUrl'),
            'fn_gravatar_exists' => new \Twig_Function($this->api, 'exists'),
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gravatar';
    }    
}
