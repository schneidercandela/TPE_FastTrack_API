<?php
require_once 'api/controllers/ReviewController.php';
require_once 'libs/Router/Router.php';

$router = new Router();

$router->addRoute("/reviews", "GET", "ReviewController", "getReviews");
$router->addRoute("/reviews/:ID", "GET", "ReviewController", "getReview");
$router->addRoute("/reviews", "POST", "ReviewController", "addReview");
$router->addRoute("/reviews/:ID", "PUT", "ReviewController", "updateReview");
$router->addRoute("/productos/:ID/reviews", "GET", "ReviewController", "getReviewPorProducto");


//Ruteo (en vez de action va resourse y el verbo)
$router->route($_REQUEST['resource'], $_SERVER['REQUEST_METHOD']);