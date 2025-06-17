<?php

use App\Utils\Links;
use KissPhp\Support\Env;
use KissPhp\Support\DatabaseParams;
use KissPhp\Support\SessionCookieParams;
use KissPhp\Support\ViewParams;

SessionCookieParams::set([
  'httponly' => true
]);

ViewParams::addGlobals([
  'static_links' => Links::STATIC_LINKS,
]);

ViewParams::addFunctions([
  'getCurrentUrl' => fn() => $_SERVER['REQUEST_URI'] ?? '/',
]);

DatabaseParams::setConnectionParams([
  'dbname' => Env::get('DB_NAME'),
  'host' => Env::get('DB_HOST'),
  'port' => (int) Env::get('DB_PORT'),
  'user' => Env::get('DB_USER'),
  'password' => Env::get('DB_PASSWORD'),
  'driver' => 'mysqli',
  'charset' => 'utf8mb4'
]);

DatabaseParams::setMetadata([
  'isDevMode' => Env::get('DEV_MODE') === 'true',
  'paths' => [__DIR__ . '/Entities'],
  'proxyDir' => (__DIR__ . '/../var/cache/doctrine/Proxies')
]);