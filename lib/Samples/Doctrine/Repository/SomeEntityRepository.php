<?php
/**
 * File contains Class SomeEntityRepository
 */

namespace Samples\Doctrine\Repository;

use Samples\Doctrine\DBAL\Connections\ExplicitSlaveConnect;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Util\Criterion\Criteria\CriteriaInterface;
use Util\Criterion\Filter;

class SomeEntityRepository
{
    use ExplicitSlaveConnect;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * SomeEntityRepository constructor.
     *
     * @param EntityManagerInterface $objectManager
     */
    public function __construct(EntityManagerInterface $objectManager)
    {
        $this->entityManager = $objectManager;
    }

    /**
     * @param Filter $filter
     *
     * @return array
     */
    public function findByCriteria(Filter $filter)
    {
        return $this->wrapIntoSlaveConnection(
            $this->entityManager->getConnection(),
            function () use ($filter) {
                $qb = $this->getQueryBuilder($filter);

                return $qb->getQuery()->getResult();
            }
        );

    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function find(int $id)
    {
        $query = <<<SQL
SELECT * FROM some_entity WHERE id = :id
SQL;
        $parameters = [
            'id' => $id,
        ];

        $types = [
            'id' => \PDO::PARAM_INT,
        ];

        $stmt = $this->executeAgainstSlave(
            $this->entityManager->getConnection(),
            'executeQuery',
            $query,
            $parameters,
            $types
        );

        return $stmt->fetch();

    }

    /**
     * @param Filter $filter
     *
     * @return QueryBuilder
     */
    private function getQueryBuilder(Filter $filter)
    {
        /** @var EntityRepository $repository */
        $repository = $this->entityManager->getRepository(SomeEntity::class);
        $qb         = $repository->createQueryBuilder('c');
        /** @var CriteriaInterface $criteria */
        foreach ($filter as $criteria) {
            $criteria->apply($qb);
        }

        return $qb;
    }
}