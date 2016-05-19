<?php

namespace Ornicar\GravatarBundle\Templating\Helper;

use Ornicar\GravatarBundle\Api\GravatarClientInterface;
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
     * @var GravatarClientInterface
     */
    protected $client;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param GravatarClientInterface $client
     * @param RouterInterface         $router
     */
    public function __construct(GravatarClientInterface $client, RouterInterface $router = null)
    {
        $this->client = $client;
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($email, $size = null, $rating = null, $default = null, $secure = null)
    {
        return $this->client->getUrl($email, $size, $rating, $default, $this->isSecure($secure));
    }

    /**
     * {@inheritdoc}
     */
    public function getUrlForHash($hash, $size = null, $rating = null, $default = null, $secure = null)
    {
        return $this->client->getUrlForHash($hash, $size, $rating, $default, $this->isSecure($secure));
    }

    /**
     * {@inheritdoc}
     */
    public function getProfileUrl($email, $secure = null)
    {
        return $this->client->getProfileUrl($email, $this->isSecure($secure));
    }

    /**
     * {@inheritdoc}
     */
    public function getProfileUrlForHash($hash, $secure = null)
    {
        return $this->client->getProfileUrlForHash($hash, $this->isSecure($secure));
    }

    public function render($email, array $options = [])
    {
        $size = isset($options['size']) ? $options['size'] : null;
        $rating = isset($options['rating']) ? $options['rating'] : null;
        $default = isset($options['default']) ? $options['default'] : null;
        $secure = $this->isSecure();

        return $this->client->getUrl($email, $size, $rating, $default, $secure);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($email)
    {
        return $this->client->exists($email);
    }

    /**
     * Returns true if avatar should be fetched over secure connection.
     *
     * @param mixed $preset
     *
     * @return Boolean
     */
    protected function isSecure($preset = null)
    {
        if (null !== $preset) {
            return !!$preset;
        }

        if (!$this->router) {
            return false;
        }

        return 'https' == strtolower($this->router->getContext()->getScheme());
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
}
