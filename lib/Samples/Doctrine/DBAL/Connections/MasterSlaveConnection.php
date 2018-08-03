<?php
/**
 * File contains Class MasterSlaveConnection
 */

namespace Samples\Doctrine\DBAL\Connections;

use Doctrine\DBAL\Connections\MasterSlaveConnection as DoctrineMasterSlaveConnection;

class MasterSlaveConnection extends DoctrineMasterSlaveConnection
{
    const MASTER_CONNECTION_NAME = 'master';
    const SLAVE_CONNECTION_NAME  = 'slave';

    /**
     * @param string|null $connectionName
     *
     * @return bool
     */
    public function connect($connectionName = null)
    {
        $connectionName = $connectionName ?: static::MASTER_CONNECTION_NAME;
        if ($this->useOnlyMaster()) {
            $connectionName = static::MASTER_CONNECTION_NAME;
        }
        return parent::connect($connectionName);
    }

    /**
     * @return bool
     */
    protected function useOnlyMaster()
    {
        $params = $this->getParams();
        return isset($params['use_only_master']) ? (bool)$params['use_only_master'] : false;
    }
}
