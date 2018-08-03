<?php
/**
 * File contains Class BalancedMasterSlaveConnection
 */

namespace Samples\Doctrine\DBAL\Connections;

use Doctrine\DBAL\Cache\QueryCacheProfile;
use Doctrine\DBAL\Connections\MasterSlaveConnection;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Samples\Doctrine\DBAL\Connections\BalanceStrategy\BalanceStrategyInterface;

class BalancedMasterSlaveConnection extends MasterSlaveConnection implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var BalanceStrategyInterface
     */
    protected $balanceStrategy;

    /**
     * @param BalanceStrategyInterface $balanceStrategy
     *
     * @return $this
     */
    public function setBalanceStrategy(BalanceStrategyInterface $balanceStrategy)
    {
        $this->balanceStrategy = $balanceStrategy;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function executeUpdate($query, array $params = [], array $types = [])
    {
        $result = parent::executeUpdate($query, $params, $types);
        $this->logConnection($query);
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function beginTransaction()
    {
        parent::beginTransaction();
        $this->logConnection('"START TRANSACTION"');
    }

    /**
     * {@inheritDoc}
     */
    public function commit()
    {
        parent::commit();
        $this->logConnection('"COMMIT"');
    }

    /**
     * {@inheritDoc}
     */
    public function rollBack()
    {
        $result = parent::rollBack();
        $this->logConnection('"ROLLBACK"');
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function exec($statement)
    {
        $result = parent::exec($statement);
        $this->logConnection($statement);
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function createSavepoint($savepoint)
    {
        parent::createSavepoint($savepoint);
        $this->logConnection('"SAVEPOINT"');
    }

    /**
     * {@inheritDoc}
     */
    public function releaseSavepoint($savepoint)
    {
        parent::releaseSavepoint($savepoint);
        $this->logConnection('"RELEASE SAVEPOINT"');
    }

    /**
     * {@inheritDoc}
     */
    public function rollbackSavepoint($savepoint)
    {
        parent::rollbackSavepoint($savepoint);
        $this->logConnection('"ROLLBACK SAVEPOINT"');
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $args = func_get_args();

        $statement = call_user_func_array([$this, 'parent::query'], $args);
        $this->logConnection($args[0]);
        return $statement;
    }

    /**
     * {@inheritDoc}
     */
    public function prepare($statement)
    {
        $result = parent::prepare($statement);
        $this->logConnection($statement);
        return $result;
    }

    /**
     * @param string                 $query
     * @param array                  $params
     * @param array                  $types
     * @param QueryCacheProfile|null $qcp
     *
     * @return \Doctrine\DBAL\Driver\Statement
     */
    public function executeQuery($query, array $params = [], $types = [], QueryCacheProfile $qcp = null)
    {
        $result = parent::executeQuery($query, $params, $types, $qcp);
        $this->logConnection($query);
        return $result;
    }

    /**
     * @param string $connectionName
     * @param array  $params
     *
     * @return mixed
     */
    protected function chooseConnectionConfiguration($connectionName, $params)
    {
        if (!$this->balanceStrategy instanceof BalanceStrategyInterface) {
            return parent::chooseConnectionConfiguration($connectionName, $params);
        }

        if ($connectionName === 'master') {
            return $params['master'];
        }

        $connections = $params['slaves'];
        $connections['master'] = $params['master'];

        $connectionKey = $this->balanceStrategy->chooseConnection($connections);
        return $connections[$connectionKey];
    }

    /**
     * @param string $query
     * @param array  $context
     */
    protected function logConnection($query = '', array $context = [])
    {
        if (!$this->logger instanceof LoggerInterface) {
            return;
        }
        $connectionType = $this->isConnectedToMaster() ? 'master' : 'slave';
        $this->logger->debug(
            sprintf("Connection type used: '%s'", $connectionType),
            array_merge(['sql' => $query], $context)
        );
    }
}
