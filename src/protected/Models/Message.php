<?php

namespace Models;


/**
 * Class Message
 * @property $currencyFrom
 * @property $currencyTo
 * @property $amountSell
 * @property $amountBuy
 * @property $rate
 * @property $timePlaced
 * @property $originatingCountry
 */
class Message
{
    protected $userid, $currencyFrom, $currencyTo, $amountSell, $amountBuy, $rate, $timePlaced, $originatingCountry;

    public function __construct($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $val) {
                if (property_exists($this, $key)) {
                    $this->$key = $val;
                }
            }
        } else {
            throw new \InvalidArgumentException("Empty message");
        }
    }

    public function __get($key)
    {
        if (property_exists($this, $key)) {
            return $this->$key;
        }
    }

}