<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/chatroom', function (Request $request, Response $response, array $args) { // Looks in template folder for all files.

    $this->logger->info("tweet-away '/chatroom' route ");

    //Render chatroom
    return $this->renderer->render($response, 'chatroom.phtml', $args);
});

$app->post('/message', function (Request $request, Response $response, array $args) {

    $this->logger->info("Chatroom '/message' route post");
    $ip = $this->ip;
    $controller = $this->message_controller;
    /**
     * @var $controller \App\FeedController
     */
    $message = $controller->PostMessage($request->getParam('Username'),$request->getParam('Message'), $ip);
    return $message;

});

$app->get('/message', function (Request $request, Response $response, array $args) { //  /history

//-----------------------------------------------------------------------------------------------------------------------
    $this->logger->info("Chatroom '/message' route get");
    $controller = $this->message_controller;
    /**
     * @var $controller \App\FeedController
     */
    $message = $controller->getFeed($request->getParam('lastPostTime'));
    return $message;

});

$app->get('/[{name}]', function (Request $request, Response $response, array $args) { // {} identifies named arguments in slim. [] makes optional
    // Sample log message
    $this->logger->info("tweet-away '/' route");
    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});


