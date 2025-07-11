<?php declare(strict_types=1);

require '../vendor/autoload.php';

use KissPhp\Services\Container;
use KissPhp\Core\DED\BoundinaryError;
use KissPhp\Core\Routing\DispatchRouter;
use KissPhp\Support\{ Env, SessionInitializer };

include '../app/settings.php';

SessionInitializer::init();
BoundinaryError::register();  
Env::load('/../');

BoundinaryError::wrap(function() {
  $uri = $_SERVER['REQUEST_URI'] ?? '';
  $uriParsed = parse_url($uri, PHP_URL_PATH) ?? '';

  $endpoint = $uriParsed === '/' ? '' : rtrim($uriParsed, '/');
  $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

  $container = Container::getInstance();
  $dispatcher = $container->get(DispatchRouter::class);
  $dispatcher->dispatch($method, $endpoint);
});
