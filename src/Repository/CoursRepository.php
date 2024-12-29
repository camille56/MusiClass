<?php

namespace App\Repository;

use App\Entity\Cours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cours>
 */
class CoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cours::class);
    }

    /**
     * Récupère les cours associés à une formation ainsi que les cours pas encore associés. ou seulement les cours non associé si le paramètre est null.
     * @param $formationId
     * @return mixed
     */
    public function getCoursDisponibles($formationId = null): mixed
    {
        $qb = $this->createQueryBuilder('c');

        if ($formationId === null) {
            // Si $formationId est null, ne récupère que les cours sans formation
            $qb->where('c.formation IS NULL');
        } else {
            // Sinon, récupère les cours liés à la formation ou sans formation
            $qb->where('c.formation IS NULL')
                ->orWhere('c.formation = :formationId')
                ->setParameter('formationId', $formationId);
        }
        return $qb->getQuery()->getResult();
    }


    //    /**
    //     * @return Cours[] Returns an array of Cours objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Cours
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
