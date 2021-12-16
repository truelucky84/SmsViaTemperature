<?php

/**
 * Interface TokenStorageInterface
 */
namespace App\Storage;

interface TokenStorageInterface
{
    /**
     * @param $key string
     * @param $token string
     *
     * @return mixed
     */
    public function set($key, $token);

    /**
     * @param $key string
     *
     * @return mixed
     */
    public function get($key);
}
