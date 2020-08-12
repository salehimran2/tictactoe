<?php

// List of valid routes 
$routes = [
    'admin'      => 'AdminController',
    'deregister' => 'DeregisterController',
    'getseeks'   => 'GetSeeksController',
    'home'       => 'HomeController',
    'login'      => 'LoginController',
    'logout'     => 'LogoutController',
    'move'       => 'MoveController',
    'seeks'      => 'SeeksController',
    'newseek'    => 'NewSeekController',
    'removeseek' => 'RemoveSeekController',
    'joinseek'   => 'JoinSeekController',
    'profile'    => 'ProfileController',
    'register'   => 'RegistrationController'
];

const DEFAULT_ROUTE = 'home';
$page = DEFAULT_ROUTE;

// Retrieve the page from the GET request and determine if valid route
if (isset($_GET['page']) && array_key_exists($_GET['page'], $routes)) {
    $page = $_GET['page'];
}

// Create and invoke the selected controller 
echo (new $routes[$page]())->call();

?>
