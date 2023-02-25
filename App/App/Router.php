<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\App;

class Router
{

    private static array $routes = [];

    public static function add(string $method,
                                string $path,
                                string $controller,
                                string $function,
                                array $middlewares = []): void
    {
        self::$routes[]= [
            'method' => $method,
            'path' => $path,
            'controller' => $controller,
            'function' => $function,
            'middleware' => $middlewares
        ];
    }
    public static function run(): void
    {
        // echo "<pre>";
        // print_r($_SERVER);die();
        // echo "</pre>";
        
        $path = '/';
        if(isset($_SERVER['PATH_INFO'])) {
            $path = $_SERVER['PATH_INFO'];
            // echo "masuk if";die();
        }
        
        $method = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            // wah parah wkwk 
            // harusnya == mas, ( untuk membandingkan )
            // kalau =, artinya ya meng-assign ulang $path nya
            // $route['path'] index ke 0, pasti ya /, jadi dia path nya ke / mulu
            // if ($path = $route['path'] && $method == $route['method']) { // kode salah 
            // kesalahan nya karena kurang teliti saja
            $pattern = "#^" . $route['path'] . "$#";
            if (preg_match($pattern, $path, $variables) && $method == $route['method']) {
                
                // call middleware
                foreach ($route['middleware'] as $middleware){
                    $instance = new $middleware;
                    $instance->before();
                }
                
                // kode benar 
                // echo "Controller : " . $route['controller'] . ', Function : ' . $route['function'];
                
                $function = $route['function'];
                $controller = new $route['controller'];
                // $controller->$function();

                array_shift($variables);
                call_user_func_array([$controller, $function], $variables);

                return;
            }
        }

        http_response_code(404);
        echo 'CONTROLLER NOT FOUND';
    }
}
