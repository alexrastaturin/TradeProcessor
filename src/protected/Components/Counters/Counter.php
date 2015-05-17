<?php

namespace Components\Counters;


use Components\Cache;
use Components\Period;
use Models\Message;

abstract class Counter
{
    /**
     * @var Cache
     */
    protected $cache;
    const STEPS = 10;

    /**
     * Amount for incrementation
     * @param Message $message
     * @return int
     */
    abstract protected function inc(Message $message);

    public function __construct(Cache $cache) {
        $this->cache = $cache;
    }

    /**
     * Increment counter
     * @param Message $message
     * @return int
     */
    public function count(Message $message)
    {
        $time = time();
        $inc = $this->inc($message);
        $country = $message->originatingCountry;
        $total = [];

        foreach (Period::periods() as $period)
        {
            $start = $time - $time % $period;
            $expire = $this->expired($period);
            $key = $this->generateKey($start, $period, $country);
            $this->cache->inc($key, $inc, $expire);
            $key = $this->generateKey($start, $period);
            $total[$period] = $this->cache->inc($key, $inc, $expire);
            $total[$period] = $this->getCnt($start, $period);
        }
        return $total;
    }

    /**
     * Return counters
     * @param $period
     * @param $countries
     * @param bool $avg
     * @return array
     */
    public function getCounters($period, $countries, $avg = false)
    {
        $time = time();
        $time = $time - $time % $period;
        $result = [];
        for ($start = $time; $start >= $time - $period * self::STEPS; $start -= $period) {
            $result[$start] = [
                'total' => $avg ? $this->getCntAvg($start, $period) : $this->getCnt($start, $period)
            ];
            foreach ($countries as $country) {
                $result[$start][$country] = $avg ? $this->getCntAvg($start, $period, $country) : $this->getCnt($start, $period, $country);
            }
        }
        return $result;
    }

    /**
     * Get counter
     * @param $start
     * @param $period
     * @param string $country
     * @return int
     */
    protected function getCnt($start, $period, $country = 'total')
    {
        $key = $this->generateKey($start, $period, $country);
        return $this->cache->get($key);
    }

    /**
     * Corrected counter for current period
     * @param $start
     * @param $period
     * @param string $country
     * @return int
     */
    protected function getCntAvg($start, $period, $country = 'total')
    {
        $cnt = $this->getCnt($start, $period, $country);
        $time = microtime(true);
        // correction if current period did not finish yet
        if ($time < $start + $period) {
            $cnt *= $period / ($time - $start);
        }
        return round($cnt);
    }

    protected function expired($period)
    {
        return $period * self::STEPS * 2;
    }

    protected function getType()
    {
        return (new \ReflectionClass($this))->getShortName();
    }

    protected function generateKey($start, $period, $country = 'total')
    {
        return $this->getType() . '_' . $period . '_' . $start . '_' . $country;
    }

}
