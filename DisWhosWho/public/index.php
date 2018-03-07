<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';

//Database Class
require '../src/Authentication/db.php';

$app = new \Slim\App;

//Authentication API
require '../src/User/auth.php';

//Todo API
//require '../src/Todo/todolist.php';


$app->run();