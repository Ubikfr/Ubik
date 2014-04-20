<?php
// Constants and Composer's autoload:
include_once '../config/global.php';
require_once '../vendor/autoload.php';;

// Start PHP Session
$session = new PhpSession('UbikSession');
// Create Pimple container
$container = new Utils_Container();
// Init Tonic App
$app = new Tonic\Application($container['resources']);
// Handle request
$options = array('mimetypes' => 'text/html');
$request = new Tonic\Request($options);

try {
    $resource = $app->getResource($request);
    $resource->container = $container;
    $response = $resource->exec();
}
catch (Tonic\NotFoundException $e) {
    $dao = new Dao_SystemPage('404', $container);
    $response = new Tonic\Response(Tonic\Response::NOTFOUND);
    $response->body = $dao->Render();
}
catch (Tonic\UnauthorizedException $e) {
    $dao = new Dao_SystemPage('401', $container);
    $response = new Tonic\Response(Tonic\Response::UNAUTHORIZED);
    $response->body = $dao->Render();
}
catch (Tonic\Exception $e) {
    $response = new Tonic\Response(500, $e);
}

$response->output();
