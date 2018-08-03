<?php
/**
 * File contains Class JWTAuthorizationFactory.php
 *
 * @since  19.08.16
 * @author Alexandra Fedotova <alexandra.fedotova@veeam.com>
 */

namespace Samples\HttpClient\Authentication\Factory;

use Samples\HttpClient\Authentication\AuthenticationOptions;
use Samples\HttpClient\Authentication\JWTAuthentication;

class JWTAuthenticationFactory
{
    /**
     * @param $container
     *
     * @return JWTAuthentication
     */
    public function __invoke($container)
    {
        $config = $container->get('Config');
        if (empty($config['sample.client']['auth']) || !is_array($config['sample.client']['auth'])) {
            throw new \RuntimeException('Config for sample client authentication is not set');
        }

        $options = new AuthenticationOptions($config['sample.client']['auth']);
        return new JWTAuthentication($options);
    }
}