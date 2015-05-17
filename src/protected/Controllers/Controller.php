<?php

namespace Controllers;


use Components\Application;

class Controller {

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Index action
     * Override it
     */
    public function indexAction()
    {
    }

    /**
     * @param $array
     */
    public function resultOK($array = array())
    {
        $this->result(['result' => 'ok', 'data' => $array]);
    }

    /**
     * @param $message
     * @param array $extra
     */
    public function resultError($message, $extra = array())
    {
        $extra = array('result' => 'error', 'message' => $message) + $extra;
        $this->result($extra);
    }

    /**
     * Render output JSON
     * @param $array
     */
    public function result($array)
    {
        header('Content-Type: application/json');
        echo json_encode($array);
    }

    public function get($param)
    {
        return isset($_GET[$param]) ? $_GET[$param] : null;
    }

}