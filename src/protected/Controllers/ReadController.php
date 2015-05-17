<?php

namespace Controllers;

use Components\Processor;

class ReadController extends Controller
{

    public function indexAction()
    {
        $processor = new Processor($this->app->getCache());

        $result = [
            'countries' => $processor->getCountries(),
            'perCountries' => $processor->getVolumeCounter($this->get('period')),
            'msgPerCountries' => $processor->getCountriesMsgCounter($this->get('period'))
        ];
        $this->resultOK($result);
    }

}