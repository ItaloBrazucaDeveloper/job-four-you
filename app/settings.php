<?php

use KissPhp\Support\Env;
use KissPhp\Support\ViewParams;
use KissPhp\Support\DatabaseParams;
use KissPhp\Support\SessionCookieParams;

SessionCookieParams::set([
  'httponly' => true,
  'secure' => false, // Em desenvolvimento, não exige HTTPS
  'samesite' => 'Lax', // Proteção básica contra CSRF
  'lifetime' => 0, // Cookie expira ao fechar o navegador
  'path' => '/',
]);

ViewParams::addFunctions([
  'getCurrentUrl' => fn() => $_SERVER['REQUEST_URI'] ?? '/',
  'getAllQueryParams' => fn() => $_GET ?? []
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