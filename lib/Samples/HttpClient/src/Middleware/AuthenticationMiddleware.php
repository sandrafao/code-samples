<?php
/**
 * File contains Class AuthorizationMiddleware.php
 */

namespace Samples\HttpClient\Middleware;

use Samples\HttpClient\Authentication\AuthenticationInterface;
use Psr\Http\Message\RequestInterface;

class AuthenticationMiddleware
{
    /**
     * @var AuthenticationInterface
     */
    protected $authService;

    /**
     * AuthorizationMiddleware constructor.
     *
     * @param AuthenticationInterface $authService
     */
    public function __construct(AuthenticationInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @param callable $handler
     *
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function(RequestInterface $request, array $options) use ($handler) {
            $request = $this->authService->authenticateRequest($request);
            return $handler($request, $options);
        };
    }
}