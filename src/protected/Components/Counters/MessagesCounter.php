<?php

namespace Components\Counters;


use Models\Message;

class MessagesCounter extends Counter
{
    protected function inc(Message $message)
    {
        return 1;
    }
}
