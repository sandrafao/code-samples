<?php
/**
 * File contains Class ClientFactory
 */

namespace Samples\HttpClient\Factory;

use Samples\HttpClient\Client;
use Samples\HttpClient\Middleware\AuthenticationMiddleware;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\HandlerStack;

class ClientFactory
{
    /**
     * @param $container
     *
     * @return Client
     */
    public function __invoke($container)
    {
        $config = $container->get('Config');
        if (empty($config['sample.client']['options']) || !is_array($config['sample.client']['options'])) {
            throw new \RuntimeException('Config for sample client is not set');
        }
        $options = $config['sample.client']['options'];
        if (empty($options['endpoint'])) {
            throw new \RuntimeException('Endpoint for sample client is not set');
        }
        if (empty($options['version'])) {
            throw new \RuntimeException('Version for sample client is not set');
        }

        /** @var AuthenticationMiddleware $authMiddleware */
        $authMiddleware = $container->get('sample.client.mw.auth');

        $stack = HandlerStack::create();
        $stack->push($authMiddleware, 'Authorization');
        $httpClient = new HttpClient(
            [
                'handler' => $stack,
                'base_uri' => $options['endpoint'],
            ]
        );
        return new Client($httpClient, $options['version']);
    }
}
