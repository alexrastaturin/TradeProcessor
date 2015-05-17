<?php

namespace Components;

use Components\Counters\MessagesCounter;
use Components\Counters\VolumeCounter;
use Models\Message;

class Processor
{

    protected $cache;
    const KEY_COUNTRIES = 'countriesSet';

    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Consume message, increment counters
     * @param Message $message
     * @return array
     */
    public function consume(Message $message)
    {
        $this->addCountry($message->originatingCountry);

        $volumeCounter = new VolumeCounter($this->cache);
        $volumeCounter->count($message);

        $messageCounter = new MessagesCounter($this->cache);
        $messageCounter->count($message);
    }

    public function getMsgCounter($period)
    {
        $messageCounter = new MessagesCounter($this->cache);
        return $messageCounter->getCounters($period, $this->getCountries(), true);
    }

    public function getVolumeCounter($period)
    {
        $messageCounter = new VolumeCounter($this->cache);
        return $messageCounter->getCounters($period, $this->getCountries());
    }

    /**
     * Return counter for countries
     * @param $period
     * @return array
     */
    public function getCountriesMsgCounter($period)
    {
        $messageCounter = new MessagesCounter($this->cache);
        $data = $messageCounter->getCounters($period, $this->getCountries());
        return count($data) ? array_shift($data) : [];
    }

    /**
     * Add country to list
     * @param $country
     */
    public function addCountry($country)
    {
        $this->cache->addSet(self::KEY_COUNTRIES, $country);
    }

    /**
     * Get country list
     * @return array
     */
    public function getCountries()
    {
        return $this->cache->getSet(self::KEY_COUNTRIES);
    }

}
