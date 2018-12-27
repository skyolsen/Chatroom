<?php
// DIC configuration
$container = $app->getContainer();
// view renderer
$container['renderer'] = function() use ($container) {
    $settings = $container->get('settings')['renderer'];
    return new \Slim\Views\PhpRenderer($settings['template_path']);
};
// database
$container['db'] = function() use ($container) {
    $settings = $container->get('settings')['db'];
    $dsn = 'mysql:dbname='.$settings['dbname'].
        ';host='.$settings['host'].
        ';port='.$settings['port'];
    $db = new \PDO($dsn, $settings['username'], $settings['password']);
    return $db;
   // In the above code, we are injecting database object into the container using dependency injection, in this case,
   // called db. So now you can access your database object with $this->db in your application,
    //and use this object to perform operations like create,update,delete,find..etc on database.
    //"Dependency Injection" is a 25-dollar term for a 5-cent concept. [...] Dependency injection means giving an object its instance variables. [...].
};
// monolog
$container['logger'] = function() use ($container) {
    $settings = $container->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
//Message Feed Controller
$container['message_controller'] = function() use ($container) {
    return new \App\MessageController($container['db'], $container['logger']);
};
// get ip
$container['ip'] = function() use ($container) {
    $ip = $_SERVER['HTTP_CLIENT_IP']?$_SERVER['HTTP_CLIENT_IP']:($_SERVER['HTTP_X_FORWARDE‌​D_FOR']?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']);
    return $ip;
};