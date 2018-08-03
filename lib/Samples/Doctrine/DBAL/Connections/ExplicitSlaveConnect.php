<?php
/**
 * File contains Class UseExplicitConnection
 */

namespace Samples\Doctrine\DBAL\Connections;

use Doctrine\DBAL\Connection;

trait ExplicitSlaveConnect
{
    /**
     * @param Connection $connection
     * @param string     $method
     * @param array      ...$args
     *
     * @return mixed
     */
    protected function executeAgainstSlave(Connection $connection, $method, ...$args)
    {
        if (!method_exists($connection, $method)) {
            throw new \BadMethodCallException(
                sprintf('Method %s does not exist in connection class %s', $method, get_class($connection))
            );
        }

        return $this->wrapIntoSlaveConnection($connection, function () use ($connection, $method, $args){
            return call_user_func_array([$connection, $method], $args);
        });
    }

    /**
     * @param Connection $connection
     * @param callable   $callback
     *
     * @return mixed
     */
    protected function wrapIntoSlaveConnection(Connection $connection, callable $callback)
    {
        try {
            $connection = $this->guardForConnectionType($connection);
        } catch (\LogicException $exception) {
            return call_user_func($callback);
        }

        $needReconnect = $connection->isConnectedToMaster();
        if ($needReconnect === true) {
            $connection->connect(MasterSlaveConnection::SLAVE_CONNECTION_NAME);
        }

        $result = call_user_func($callback);

        if ($needReconnect === true) {
            $connection->connect(MasterSlaveConnection::MASTER_CONNECTION_NAME);
        }

        return $result;
    }

    /**
     * @param Connection $connection
     *
     * @return MasterSlaveConnection
     */
    protected function guardForConnectionType(Connection $connection)
    {
        if (!$connection instanceof MasterSlaveConnection) {
            throw new \LogicException(
                sprintf(
                    'Master/slave db connection is not supported. Connection should be instance of %s. %s given',
                    MasterSlaveConnection::class,
                    get_class($connection)
                )
            );
        }
        return $connection;
    }
}