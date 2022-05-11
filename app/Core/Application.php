<?php

namespace App\Core;

class Application
{
    public static Application $app;
    public Router $router;
    public Request $request;

    public function __construct() {
        self::$app = $this;
        $this->request = new Request();
        $this->router = new Router($this->request);
    }

    public function run() {
        $this->router->resolve();
    }

}
