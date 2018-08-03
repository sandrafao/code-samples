<?php
/**
 * File contains Class AuthorizationInterface.php
 *
 * @since  19.08.16
 * @author Alexandra Fedotova <alexandra.fedotova@veeam.com>
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