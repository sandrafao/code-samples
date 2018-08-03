Code samples
------------
This repository contains 2 code samples:

1) Samples\Doctrine 
    - Extending doctrine MasterSlaveConnection class to use slave connection only for specific queries
    - Samples\Doctrine\DBAL\Connections\BalancedMasterSlaveConnection 
        Extending doctrine MasterSlaveConnection to split reads not only between slaves but between master and slaves. 
        Support inject another balancing strategy
        
2) Samples\HttpClient
    Simple implementation for HttpClient with JWT Authentication
