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
$route->get('/status', 'Web:status');


/**
 * APP
 */
$route->group('/app');
$route->get('/', 'App:home');

// ROTA PARA STATUS DE SERVIÇO (ACESSO EM /app/status)
$route->get('/status', 'App:serviceStatus'); 
$route->post('/status/save', 'App:saveServiceStatusPost');
$route->delete('/status/delete/{id}', 'App:deleteServiceStatus');
$route->get('/status/data', 'App:getServiceStatusData'); // Nova rota para dados do DataTables

//Equipamentos
// $route->get('/equipamentos', 'App:equipments');
// $route->get('/equipamento/{id}', 'App:editEquipment');
// $route->get('/equipamento', 'App:equipment');
// $route->post('/equipamento', 'App:saveEquipment');
// $route->delete('/equipamento/{id}', 'App:deleteEquipment');

$route->get("/equipamentos", "App:equipments");
$route->get("/equipamentos/{page}/{limit}", "App:equipments");
$route->get("/equipamento/{id}", "App:equipment");
$route->get("/equipamento/criar", "App:equipment");
$route->get("/equipment/delete/{id}", "App:deleteEquipment");

$route->post("/equipments", "App:equipments");
$route->post("/equipment/save", "App:saveEquipmentPost");

//Funcionários
$route->get("/funcionarios", "App:employees");
$route->get("/funcionarios/{page}/{limit}", "App:employees");
$route->get("/funcionario/{id}", "App:employee");
$route->get("/funcionario/associar", "App:employeeAssign");
$route->get("/funcionario/delete/{id}", "App:deleteEmployee");

$route->post("/funcionarios", "App:employees");
$route->post("/funcionario/associar/salvar", "App:saveEmployeeAssignPost");
$route->post("/funcionarios/salvar", "App:saveEmployeePost");

// Usuários
$route->get('/usuarios', 'App:users');
$route->get("/usuarios/{page}/{limit}", "App:users");

$route->get('/usuario/{id}',       'App:user');
$route->get('/usuario/criar',       'App:user');


$route->post('/users', 'App:users');

$route->post('/users/save',         'App:saveUserPost');

$route->post('/users/roles',        'App:saveUserRolesPost');

// Planos
$route->get('/planos', 'App:plans');
$route->get('/plano/{id}', 'App:planForm'); // editar plano
$route->get('/plano/novo', 'App:planForm'); // novo plano
$route->post('/plans/save', 'App:savePlan');

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
$route->get('/sair', 'App:logout');

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
