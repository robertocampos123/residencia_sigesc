<?php

namespace App\Repository;

use App\Entity\Calificaciones;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Calificaciones>
 *
 * @method Calificaciones|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calificaciones|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calificaciones[]    findAll()
 * @method Calificaciones[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalificacionesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calificaciones::class);
    }

    public function save(Calificaciones $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Calificaciones $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function eliminarCal($grupo, $alumno)
        {
            return $this->createQueryBuilder('c')
                        ->delete('c')
                        ->where('c.grupo = :grupo AND c.alumno = :alumno')
                        ->setParameter('grupo', $grupo)
                        ->setParameter('alumno', $alumno)
                        ->getQuery()
                        ->getResult()
                        ;
        }

        public function BuscarCal1($grupo):array
        {
            return $this->createQueryBuilder('c')
                        ->innerJoin('c.alumno', 'alu')
                        ->select('alu.numero_control as control, alu.apellido_paterno as paterno, c.periodo_uno as calificacion')
                        ->where('c.grupo = :grupo')
                        ->setParameter('grupo', $grupo)
                        ->getQuery()
                        ->getResult()
                        ;
        }

        public function BuscarCal2($grupo):array
        {
            return $this->createQueryBuilder('c')
                        ->innerJoin('c.alumno', 'alu')
                        ->select('alu.numero_control as control, alu.apellido_paterno as paterno, c.periodo_dos as calificacion')
                        ->where('c.grupo = :grupo')
                        ->setParameter('grupo', $grupo)
                        ->getQuery()
                        ->getResult()
                        ;
        }

        public function BuscarCal3($grupo):array
        {
            return $this->createQueryBuilder('c')
                        ->innerJoin('c.alumno', 'alu')
                        ->select('alu.numero_control as control, alu.apellido_paterno as paterno, c.periodo_tres as calificacion')
                        ->where('c.grupo = :grupo')
                        ->setParameter('grupo', $grupo)
                        ->getQuery()
                        ->getResult()
                        ;
        }

//    /**
//     * @return Calificaciones[] Returns an array of Calificaciones objects
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

//    public function findOneBySomeField($value): ?Calificaciones
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
