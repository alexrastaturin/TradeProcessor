<?php

namespace Controllers;


use Components\Processor;
use Models\Message;

class ConsumeController extends Controller
{

    public function indexAction()
    {
        $input = file_get_contents('php://input');
//        $input = '{"userId": "134256", "currencyFrom": "EUR", "currencyTo": "GBP", "amountSell": 1000, "amountBuy": 747.10, "rate": 0.7471, "timePlaced" : "24-JAN-15 10:27:44", "originatingCountry" : "FR"}';

        $data = json_decode($input, true);

        $message = new Message($data);
        $processor = new Processor($this->app->getCache());
        $this->resultOK($processor->consume($message));
    }
}