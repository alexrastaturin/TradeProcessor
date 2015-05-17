<?php

namespace Components\Counters;

use Models\Message;

class VolumeCounter extends Counter
{
    protected function inc(Message $message)
    {
        return $message->amountBuy;
    }
}
