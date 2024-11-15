<?php
declare(strict_types=1);
namespace Framework;



use ReflectionMethod;
use Framework\Container;
use Framework\Exceptions\PageNotFoundException;

class Dispatcher
{
    public function __construct(private Router $router, private Container $container)
    {

    }

    public function handle(string $path)
    {
        $params = $this->router->match($path);


        if ($params === false) {
            throw new PageNotFoundException("No route matches for the $path");
        }

        $controller = $this->getControllerNames($params);
        $action = $this->getActionNames($params);

        $controller_object = $this->container->get($controller);
        $args = $this->getActionArguments($controller, $action, $params);
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
        $controller = str_replace("-", "", ucwords(strtolower($controller), '-'));
        $namespace = "App\Controllers";
        if (array_key_exists("namespace", $params)) {
            $namespace .= "\\" . $params["namespace"];
        }

        return $namespace . "\\" . $controller;
    }
    private function getActionNames(array $params): string
    {
        $action = $params["action"];
        $action = lcfirst(str_replace("-", "", ucwords(strtolower($action), '-')));
        return $action;
    }
    

}