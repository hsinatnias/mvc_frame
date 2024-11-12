<?php

namespace Framework;

use ReflectionMethod;

class Dispatcher
{
    public function __construct(private Router $router)
    {

    }

    public function handle(string $path)
    {
        $params = $this->router->match($path);


        if ($params === false) {
            exit("No route matches");
        }

        $controller = "App\Controllers\\" . ucwords($params["controller"]);
        $action = $params["action"];
        $args = $this->getActionArguments($controller, $action, $params);
        $controller_object = new $controller();
        $controller_object->$action(...$args);
    }

    private function getActionArguments(string $controller, string $action, array $params): array
    {
        $args = [];
        $method = new ReflectionMethod($controller, $action);
        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            $args[$name] = $params[$name];
        }

        return $args;
    }

}