<?php

namespace App\Core;

class Router
{
    public Request $request;
    public static  array   $routes = [];

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request =$request;
    }


    public static function get(string $path, $callback) {
        self::$routes['get'][$path] = $callback;
    }

    public static function post(string $path, $callback) {
    self::$routes['post'][$path] = $callback;
    }

    public function resolve() {
        $path = $this->request->getPatch();
        $method = $this->request->getMethod();
        $callback =  self::$routes[$method][$path] ?? false;
        if (!$callback) {
            http_response_code(404);
            include(VIEW_ROOT . '404.php'); // provide your own HTML for the error page
            die();
        }
        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
        }

        call_user_func($callback, $this->request);
    }
}