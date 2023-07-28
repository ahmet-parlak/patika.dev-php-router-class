<?php
require 'Router.php';

$router = new Router();

$router->run('/', function () {
    echo "<h1>Router</h1>";
}, 'get|post');

$router->get('/users', 'users@index');

$router->get('/user/{id}', 'users@show');

$router->get('/test/{url}', function ($test) {
    echo $test;
});

$router->post('/users', 'users@create');

$router->endRouter();



?>