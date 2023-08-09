<?php

namespace App\Repository;

use App\Entity\Materia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Materia>
 *
 * @method Materia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Materia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Materia[]    findAll()
 * @method Materia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MateriaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Materia::class);
    }

    public function save(Materia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Materia $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function queryMateria()
    {
        return $this->getEntityManager()
                    ->createQuery(
                    'SELECT m.id, m.clave_materia, m.nombre_materia, m.horas_teoricas, m.horas_practicas, 
                    m.creditos, m.plan_academico, m.semestre
                    FROM App:Materia m
                    ORDER BY m.clave_materia ASC'
                    );                    
    }

    

//    /**
//     * @return Materia[] Returns an array of Materia objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Materia
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
