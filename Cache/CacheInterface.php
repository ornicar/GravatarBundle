<?php

namespace Ornicar\GravatarBundle\Cache;

/**
 * All caches storages should implement this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface CacheInterface
{
    /**
     * Has entry in storage?
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key);

    /**
     * Write entry to cache storage
     *
     * @param string $key
     * @param mixed $value
     *
     * @return bool
     */
    public function set($key, $value);

    /**
     * Get entry from cache storage
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);
}
