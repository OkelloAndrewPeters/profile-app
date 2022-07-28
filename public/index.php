<?php
require '../vendor/autoload.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Slim\Http\Response as Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$pdo = require './db.php';
$key = "3qp24HtYc2XQFgnAiKTYLivtH0v9CSIwHtYaXOgxUfuh-N-0e4TYO4SVuB-s9ypXEfMLX_IgsrA9NNdNFV0mZxA-aQAHcksG0WZQPqvsnsj8YF-4QLLCZ6pFs-opJrx2tuLEDtqK28MB0ZTZD4CQzukMdpmJ8b8UWhF4NRBrrP8";

$app = new \Slim\App;

$app->add(function (Request $request, Response $response, callable $next) {
    $uri = $request->getUri();
    $path = $uri->getPath();
    if ($path != '/' && substr($path, -1) == '/') {
        // recursively remove slashes when its more than 1 slash
        while (substr($path, -1) == '/') {
            $path = substr($path, 0, -1);
        }

        // permanently redirect paths with a trailing slash
        // to their non-trailing counterpart
        $uri = $uri->withPath($path);

        if ($request->getMethod() == 'GET') {
            return $response->withRedirect((string)$uri, 301);
        } else {
            return $next($request->withUri($uri), $response);
        }
    }

    return $next($request, $response);
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->get('/hello/{name}', function (Request $request, Response $response, array $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->post('/register', function (Request $request, Response $response) {
    $body = $request->getParsedBody();
    if (isset($body['firstName']) && isset($body['lastName']) && isset($body['phoneNumber']) && isset($body['password']) && !empty(trim($body['firstName'])) && !empty(trim($body['lastName'])) && !empty(trim($body['phoneNumber'])) && !empty($body['password'])) {
        insertUser(trim($body['firstName']), trim($body['lastName']), trim($body['phoneNumber']), $body['password']);
        unset($body['password']);
        return $response->withJson($body);
    }
    return $response->withStatus(400)->withJson(['error' => 'Missing data']);
});

$app->post('/login', function (Request $request, Response $response) {
    global $key;
    $body = $request->getParsedBody();
    if (isset($body['phoneNumber']) && isset($body['password']) && !empty(trim($body['phoneNumber'])) && !empty($body['password'])) {
        $user = getUser(trim($body['phoneNumber']));
        if (empty($user) || !password_verify($body['password'], $user['password_hash'])) {
            return $response->withStatus(400)->withJson(['error' => 'Invalid phone number or password']);
        }
        unset($user['password_hash']);
        unset($user['first_name']);
        unset($user['last_name']);
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;
        $user['iat'] = $issuedAt;
        $user['exp'] = $expirationTime;
        return $response->withJson(['token' => JWT::encode($user, $key, "HS256")]);
    }
    return $response->withStatus(400)->withJson(['error' => 'Missing data']);
});

$app->post('/profile-data', function (Request $request, Response $response, array $args) {
    global $key;
    $body = $request->getParsedBody();
    $phoneNumber = trim($body['phoneNumber']);
    if (!empty($body['token']) && !empty($phoneNumber) && !empty(trim($phoneNumber))) {
        try {
            $decoded = JWT::decode($body['token'], new Key($key, 'HS256'));
            $decoded = (array) $decoded;
            if ($decoded['phone_number'] == $phoneNumber) {
                $user = getUser($phoneNumber);
                if (empty($user)) {
                    return $response->withStatus(404)->withJson(['error' => 'No such profile']);
                }
                unset($user['password_hash']);
                return $response->withJson($user);
            }
            return $response->withStatus(401)->withJson(['error' => 'Unauthorized access']);
        } catch (\Firebase\JWT\ExpiredException $e) {
            return $response->withStatus(401)->withJson(['error' => 'Token expired']);
        }
    }
    return $response->withStatus(400)->withJson(['error' => 'Missing token or phone number']);
});

$app->get('/[{path:.*}]', function (Request $request, Response $response, $args) {
    if (!array_key_exists("path", $args)) {
        $response->getBody()->write(file_get_contents(__DIR__ . '/index.html'));
        return $response;
    }

    $filePath = __DIR__ . '/' . $args['path'];

    if (!file_exists($filePath)) {
        $response->getBody()->write(file_get_contents(__DIR__ . '/index.html'));
        return $response;
    }

    switch (pathinfo($filePath, PATHINFO_EXTENSION)) {
        case 'css':
            $mimeType = 'text/css';
            break;

        case 'js':
            $mimeType = 'application/javascript';
            break;

            // Add more supported mime types per file extension as you need here

        default:
            $mimeType = 'text/html';
    }

    $newResponse = $response->withHeader('Content-Type', $mimeType . '; charset=UTF-8');

    $newResponse->getBody()->write(file_get_contents($filePath));

    return $newResponse;
});

$app->run();
