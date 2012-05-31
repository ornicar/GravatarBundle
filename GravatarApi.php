<?php

namespace Ornicar\GravatarBundle;

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
        'proxy' => false
    );

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
    public function getUrl($email, $size = null, $rating = null, $default = null, $secure = false)
    {
        $hash = md5(strtolower($email));

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
    public function getUrlForHash($hash, $size = null, $rating = null, $default = null, $secure = false)
    {
        $map = array(
            's' => $size    ?: $this->defaults['size'],
            'r' => $rating  ?: $this->defaults['rating'],
            'd' => $default ?: $this->defaults['default'],
        );

        return ($secure ? 'https://secure' : 'http://www') . '.gravatar.com/avatar/' . $hash . '?' . http_build_query(array_filter($map));
    }

    /**
     * Checks if a gravatar exists for the email. It does this by checking for 404 Not Found in the
     * body returned.
     *
     * @param string $email
     * @return Boolean
     */
    public function exists($email)
    {
        $path = $this->getUrl($email, null, null, '404');
        die(var_dump($proxy));
        if(true === $this->defaults['proxy']) {
        
            // user cURL if we're using a proxy
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $path);
            curl_setopt($ch, CURLOPT_PROXY, $this->defaults['proxy']['url']);
            curl_setopt($ch, CURLOPT_PROXYPORT, $this->defaults['proxy']['port']);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            
            $data = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);  
            curl_close($ch);
        
            return $httpCode >= 200 && $httpCode < 300 ? true : false;  
        
        }
        else {
             
            $path = $this->getUrl($email, null, null, '404');

            $sock = fsockopen('gravatar.com', 80, $errorNo, $error);
            fputs($sock, "HEAD " . $path . " HTTP/1.0\r\n\r\n");

            $header = fgets($sock, 128);

            fclose($sock);

            return trim($header) == 'HTTP/1.1 404 Not Found' ? false : true;   
        
        }
    }
}
