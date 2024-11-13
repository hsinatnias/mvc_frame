<?php

namespace Framework;


use ReflectionClass;
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

        $controller = $this->getControllerNames($params);
        $action = $this->getActionNames($params);

        $reflector = new ReflectionClass($controller);
        $constructor = $reflector->getConstructor();

        $dependencies = [];

        if($constructor !== null) {
            foreach ($constructor->getParameters() as $parameter) {
                $type = (string) $parameter->getType();
                $dependencies[] = new $type;
            }
        }

        $args = $this->getActionArguments($controller, $action, $params);
        $controller_object = new $controller(...$dependencies);
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
    private function getControllerNames(array $params): string
    {
        $controller = $params["controller"];
        $controller = str_replace("-", "",  ucwords(strtolower($controller), '-'));
        $namespace = "App\Controllers";
        if(array_key_exists("namespace", $params)) {
            $namespace .= "\\".$params["namespace"];
        }

        return $namespace . "\\" . $controller;
    }
    private function getActionNames(array $params): string
    {
        $action = $params["action"];
        $action = lcfirst(str_replace("-", "",  ucwords(strtolower($action), '-')));
        return $action;
    }

}