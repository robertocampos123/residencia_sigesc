<?php

namespace App\Repository;

use App\Entity\ReporteFinal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReporteFinal>
 *
 * @method ReporteFinal|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReporteFinal|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReporteFinal[]    findAll()
 * @method ReporteFinal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReporteFinalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReporteFinal::class);
    }

    public function save(ReporteFinal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReporteFinal $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    
    public function listarReporteFinal($semestre,$departamento): array
    {
        return $this->createQueryBuilder('r')
                    ->innerJoin('r.empleado', 'emp')
                    ->select('emp.nombre_empleado, emp.apellido_paterno, emp.apellido_materno, 
                              r.reporte_final, r.Periodo, r.id, r.estado')
                    ->where('r.Periodo = :semestre AND emp.departamento = :departamento')
                    ->setParameter('departamento', $departamento)
                    ->setParameter('semestre', $semestre)
                    ->orderBy('emp.apellido_paterno','ASC')
                    ->getQuery()
                    ->getResult();
    }
    public function query_reporteFinal($semestre, $departamento)
    {
        return $this->getEntityManager()
                    ->createQuery('SELECT emp.nombre_empleado, emp.apellido_paterno, emp.apellido_materno,
                     r.reporte_final, r.Periodo, r.id, r.estado
                     FROM App:ReporteFinal r
                     JOIN r.empleado emp
                     WHERE r.Periodo = :semestre 
                     AND emp.departamento = :departamento
                     ORDER BY emp.apellido_paterno ASC')
                    ->setParameter('departamento', $departamento)
                    ->setParameter('semestre', $semestre);
    }

//    /**
//     * @return ReporteFinal[] Returns an array of ReporteFinal objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReporteFinal
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
