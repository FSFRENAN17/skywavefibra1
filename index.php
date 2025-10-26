<?php

date_default_timezone_set('America/Sao_Paulo');
ini_set('xdebug.var_display_max_depth', -1);
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);

ob_start();

require __DIR__ . '/vendor/autoload.php';

/**
 * BOOTSTRAP
 */

use CoffeeCode\Router\Router;
use Source\Core\Session;

$session = new Session();
$route = new Router(url(), ':');
$route->namespace("Source\App");

/**
 * WEB
 */
$route->group(null);
$route->get('/', 'Web:home');

//auth
$route->group(null);
$route->get('/entrar', 'Web:login');
$route->get('/cadastrar', 'Web:register');
$route->get('/recuperar', 'Web:forget');
$route->get('/recuperar/{email}/{code}', 'Web:reset');

$route->post('/entrar', 'Web:login');
$route->post('/pre-register', 'Web:preRegister');
$route->post('/register', 'Web:register');
$route->post('/recover', 'Web:forget');
$route->post('/recover/reset', 'Web:reset');

//optin
$route->group(null);
$route->get('/confirma/{email}', 'Web:confirm');
$route->post('/confirm', 'Web:confirm');
$route->post('/confirm/{resendCode}/{email}', 'Web:confirm');
$route->get('/obrigado/{email}', 'Web:success');

//services
$route->group(null);
$route->get('/termos', 'Web:terms');
$route->get('/privacidade', 'Web:privacy');


/**
 * APP
 */
$route->group('/app');
$route->get('/', 'App:home');

//Equipamentos
$route->get('/equipamento/{id}', 'App:editEquipment');
$route->get('/equipamento', 'App:equipment');
$route->get('/equipamentos', 'App:equipments');
$route->post('/equipamento', 'App:saveEquipment');
$route->delete('/equipamento/{id}', 'App:deleteEquipment');


// Usuários
$route->get('/usuarios', 'App:users');
$route->get("/usuarios/{page}/{limit}", "App:users");

$route->get('/usuario/{id}',       'App:user');
$route->get('/usuario/criar',       'App:user');


$route->post('/users', 'App:users');

$route->post('/users/save',         'App:saveUserPost');

$route->post('/users/roles',        'App:saveUserRolesPost');

// CLientes
$route->get('/clientes', 'App:customers');

// Página para gerenciar/associar cliente — vamos abrir a mesma view para buscar por CPF
$route->get('/cliente/{id}', 'App:clientForm'); // caso queira editar por id do person/customer
$route->get('/cliente/novo', 'App:clientForm');

// AJAX: busca por CPF (POST)
$route->post('/clientes/buscar', 'App:searchClientByCpf');

// AJAX: salvar cliente / alocar equipamento / definir plano
$route->post('/clientes/save', 'App:saveCustomer');



// Perfil

$route->get('/perfil', 'App:profile');
$route->post("/profile-save", "App:profileSave");


//Logout
$route->get('/sair', 'Web:logout');

//END ROUTES
$route->namespace("Source\App");

/**
 * ERROR ROUTES
 */
$route->group('/ops');
$route->get('/{errcode}', 'Web:error');

/**
 * ROUTE
 */
$route->dispatch();

/**
 * ERROR REDIRECT
 */
if ($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();
