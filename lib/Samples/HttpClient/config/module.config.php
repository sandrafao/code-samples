<?php

use Samples\HttpClient\Authentication\Factory\JWTAuthenticationFactory;
use Samples\HttpClient\Authentication\JWTAuthentication;
use Samples\HttpClient\Client;
use Samples\HttpClient\Factory\ClientFactory;
use Samples\HttpClient\Middleware\AuthenticationMiddleware;
use Samples\HttpClient\Middleware\Factory\AuthenticationMiddlewareFactory;

return [
    'sample.client'         => [
        'options' => [
            'version'  => 'v1',
            'endpoint' => '',
        ],
        'auth'    => [
            'issuer'     => '',
            'secret_key' => '',
            'token_ttl'  => '',
        ],
    ],
    'service_manager'    => [
        'aliases'            => [
            'sample.client.auth.jwt' => JWTAuthentication::class,
            'sample.client.mw.auth'  => AuthenticationMiddleware::class,
        ],
        'factories'          => [
            JWTAuthentication::class        => JWTAuthenticationFactory::class,
            AuthenticationMiddleware::class => AuthenticationMiddlewareFactory::class,
            Client::class => ClientFactory::class,
        ],
    ],
];