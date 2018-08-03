<?php
/**
 * File contains Class AuthorizationMiddlewareFactory.php
 */

namespace Samples\HttpClient\Middleware\Factory;

use Samples\HttpClient\Authentication\AuthenticationInterface;
use Samples\HttpClient\Middleware\AuthenticationMiddleware;

class AuthenticationMiddlewareFactory
{
    public function __invoke($container)
    {
        /** @var AuthenticationInterface $authService */
        $authService = $container->get('client.auth.jwt');

        return new AuthenticationMiddleware($authService);
    }
}