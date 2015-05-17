<?php

namespace Components;


use Controllers\ConsumeController;
use Controllers\Controller;
use Controllers\ReadController;

class Application {

    protected $config;
    protected $cache;
    /**
     * @var Controller
     */
    protected $controller;

    /**
     * Init application
     * @param $path
     * @return Application
     */
    public static function init($path)
    {
        // Enable CORS
        header("Access-Control-Allow-Origin: *");

        error_reporting(E_ALL ^ E_NOTICE);
        ini_set("display_errors", 1);

        spl_autoload_register(array('Components\Application', 'autoload'));

        $app = new Application();

        $app->config = include($path);

        $app->cache = new Cache(
            $app->config['cache']['host'],
            $app->config['cache']['port']
        );

        return $app;
    }

    public function run()
    {
        try {
            $this->controller->indexAction();
        } catch (\Exception $e) {
            echo json_encode(['result' => 'error', 'message' => $e->getMessage() . $e->getTraceAsString()]);
        }
    }

    public function runConsume() {
        $this->controller = new ConsumeController($this);
        $this->run();
    }

    public function runRead() {
        $this->controller = new ReadController($this);
        $this->run();
    }

    public static function autoload($class)
    {
        $path = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($path)) {
            include_once $path;
        }
    }

    /**
     * @return \Components\Cache
     */
    function getCache()
    {
        return $this->cache;
    }

}