<?php

namespace Components;


/**
 * Facade for working with Cache
 */
class Cache
{
    /** @var  \Memcache */
    protected $memcached;

    protected $type;

    /**
     * @return \Memcache
     */
    protected function getCache()
    {
        return $this->memcached;
    }

    public function __construct($host, $port)
    {
        if(class_exists('\Memcache')){
            $this->memcached = new \Memcache;
        } elseif(class_exists('\Memcached')){
            $this->memcached = new \Memcached;
        } else {
            throw new \ErrorException("Neither Memcached nor Memcache was installed");
        }
        $this->memcached->addServer($host, $port);
    }

    public function set($key, $value, $expire = 0)
    {
        if ($this->memcached instanceof \Memcached) {
            return $this->memcached->set($key, $value, $expire);
        } else {
            return $this->memcached->set($key, $value, false, $expire);
        }
    }

    /**
     * Return cache value
     * @param $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = 0)
    {
        $value = $this->getCache()->get($key);
        return $value === false ? $default : $value;
    }

    public function del($key)
    {
        $this->getCache()->delete($key);
    }

    public function inc($key, $val = 1, $expire = Period::SEC)
    {
        $this->initValue($key, $expire);
        return $this->getCache()->increment($key, $val);
    }

    public function addSet($key, $val, $expire = null)
    {
        if (!in_array($val, $arr = $this->getSet($key))) {
            $arr[] = $val;
            $this->set('set' . $key, $arr, $expire);
        }
    }

    /**
     * Return set of values
     * @param $key
     * @return array
     */
    public function getSet($key)
    {
        return array_unique($this->get('set' . $key, []));
    }

    protected function initValue($key, $expire)
    {
        if ($this->memcached instanceof \Memcached) {
            $this->getCache()->add($key, 0, $expire);
        } else {
            $this->getCache()->add($key, 0, false, $expire);
        }
    }

}