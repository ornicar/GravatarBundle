<?php

namespace Ornicar\GravatarBundle\Cache;

/**
 * File system cache
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class FileCache implements CacheInterface
{
    /**
     * @var string
     */
    private $directory;

    /**
     * @var integer
     */
    private $lifetime;

    /**
     * Constructor.
     *
     * @param string  $directory
     * @param integer $lifetime
     */
    public function __construct($directory, $lifetime = 86400)
    {
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0755, true)) {
                throw new \RuntimeException(sprintf(
                    'Can not create directory "%s".',
                    $directory
                ));
            }
        }

        if (!is_writable($directory)) {
            throw new \RuntimeException(sprintf(
                'The directory "%s" is not writable.',
                $directory
            ));
        }

        $this->directory = $directory;
        $this->lifetime = $lifetime;
    }

    /**
     * {@inheritDoc}
     */
    public function has($key)
    {
        $path = $this->generateFileNameByKey($key);

        if (!file_exists($path)) {
            return false;
        }

        $entry = file_get_contents($path);
        $entry = unserialize($entry);

        return time() < $entry['lifetime'];
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value)
    {
        $data = array(
            'lifetime' => time() + $this->lifetime,
            'value'    => $value,
        );

        $content = serialize($data);
        $path = $this->generateFileNameByKey($key);
        $dir = dirname($path);

        if (!is_dir($dir)) {
            if (false === @mkdir($dir, 0755, true)) {
                throw new \RuntimeException(sprintf(
                    'Can not create directory "%s".',
                    $dir
                ));
            }
        }

        file_put_contents($path, $content);
    }

    /**
     * {@inheritDoc}
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            return null;
        }

        $path = $this->generateFileNameByKey($key);
        $entry = file_get_contents($path);
        $entry = unserialize($entry);

        if (time() > $entry['lifetime']) {
            return null;
        }

        return $entry['value'];
    }

    /**
     * Generate file name by key
     *
     * @param string $key
     *
     * @return string
     */
    private function generateFileNameByKey($key)
    {
        $key = md5($key);
        $parts = str_split($key, 2);

        $path = $this->directory;

        for ($i = 0; $i < 3; $i++) {
            $path .= '/' . array_shift($parts);
        }

        $path .= '/' . implode('', $parts);

        return $path;
    }
}
