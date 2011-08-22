<?php

namespace Ornicar\GravatarBundle\Twig;

use Ornicar\GravatarBundle\Templating\Helper\GravatarHelper,
    Ornicar\GravatarBundle\Templating\Helper\GravatarHelperInterface;

/**
 * @author Thibault Duplessis
 * @author Henrik Bjornskov   <hb@peytz.dk>
 */
class GravatarExtension extends \Twig_Extension implements GravatarHelperInterface
{
    /**
     * @var GravatarHelper $baseHelper
     */
    protected $baseHelper;

    /**
     * @param GravatarApi $api
     */
    public function __construct(GravatarHelper $helper)
    {
        $this->baseHelper = $helper;
    }

    public function getFunctions()
    {
        return array(
            'gravatar'          => new \Twig_Function_Method($this, 'getUrl'),
            'gravatar_hash'     => new \Twig_Function_Method($this, 'getUrlForHash'),
            'gravatar_exists'   => new \Twig_Function_Method($this, 'exists'),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl($email, $size = null, $rating = null, $default = null, $secure = null)
    {
        return $this->baseHelper->getUrl($email, $size, $rating, $default, $secure);
    }

    /**
     * {@inheritDoc}
     */
    public function getUrlForHash($hash, $size = null, $rating = null, $default = null, $secure = null)
    {
        return $this->baseHelper->getUrlForHash($hash, $size, $rating, $default, $secure);
    }

    /**
     * {@inheritDoc}
     */
    public function exists($email)
    {
        return $this->baseHelper->exists($email);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gravatar';
    }
}
