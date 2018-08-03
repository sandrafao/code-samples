<?php
/**
 * File contains Class AuthorizationInterface.php
 */

namespace Samples\HttpClient\Authentication;

use Psr\Http\Message\RequestInterface;

interface AuthenticationInterface
{
    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function authenticateRequest(RequestInterface $request);
}