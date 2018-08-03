<?php
/**
 * File contains Class RandomStrategy
 */

namespace Samples\Doctrine\DBAL\Connections\BalanceStrategy;

class RandomStrategy implements BalanceStrategyInterface
{
    /**
     * @param array $connections
     *
     * @return string
     */
    public function chooseConnection(array $connections)
    {
        return array_rand($connections);
    }
}
