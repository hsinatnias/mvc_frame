<?php


$container = new \Framework\Container;

$container->set(App\Database::class, function () {
    return new App\Database("localhost", "product_db", "root", "");
});

return $container;