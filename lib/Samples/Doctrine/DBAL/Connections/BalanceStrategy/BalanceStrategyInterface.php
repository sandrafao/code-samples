<?php
/**
 * File contains Class BalanceStrategyInterface
 */

namespace Samples\Doctrine\DBAL\Connections\BalanceStrategy;

interface BalanceStrategyInterface
{
    /**
     * @param array $connections
     *
     * @return string
     */
    public function chooseConnection(array $connections);
}