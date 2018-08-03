<?php
/**
 * File contains Class JWTMiddleware.php
 *
 * @since  18.08.16
 * @author Alexandra Fedotova <alexandra.fedotova@veeam.com>
 */

namespace Samples\HttpClient\Authentication;


use Firebase\JWT\JWT;
use Psr\Http\Message\RequestInterface;

class JWTAuthentication implements AuthenticationInterface
{
    /**
     * @var AuthenticationOptionsInterface
     */
    protected $options;

    /**
     * JWTAuthorizationMiddleware constructor.
     *
     * @param AuthenticationOptionsInterface $options
     */
    public function __construct(AuthenticationOptionsInterface $options)
    {
        $this->options = $options;
    }

    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function authenticateRequest(RequestInterface $request)
    {
        $request = $request->withHeader('JWT', $this->getJWTToken());
        return $request;
    }

    /**
     * @return string
     */
    private function getJWTToken()
    {
        $now = time();
        return JWT::encode(
            [
                'iss'      => $this->options->getIssuer(),
                'iat'      => $now,
                'exp'      => ($now + $this->options->getTokenTtl()),
            ],
            $this->options->getSecretKey()
        );
    }
}