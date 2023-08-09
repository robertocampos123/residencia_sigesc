<?php

namespace App\Repository;

use App\Entity\Alumno;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Alumno>
 *
 * @method Alumno|null find($id, $lockMode = null, $lockVersion = null)
 * @method Alumno|null findOneBy(array $criteria, array $orderBy = null)
 * @method Alumno[]    findAll()
 * @method Alumno[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlumnoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Alumno::class);
    }

    public function save(Alumno $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Alumno $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function todosAlumnos()
    {
        return $this->createQueryBuilder('a')
                    ->innerJoin('a.carrera', 'car')
                    ->select('a.id, a.numero_control, a.nombre_alumno, a.apellido_paterno, a.apellido_materno, car.clave_carrera')
                    ->orderBy('a.numero_control', 'DESC')
                    ->getQuery();
    }

    public function todosAlumno()
    {
        return $this->getEntityManager()
                    ->createQuery(
                    'SELECT a.id, a.numero_control, a.nombre_alumno, a.apellido_paterno, a.apellido_materno, car.clave_carrera as clave
                    FROM App:Alumno a
                    JOIN a.carrera car
                    ORDER BY a.numero_control ASC'
                    );                    
    }

//    /**
//     * @return Alumno[] Returns an array of Alumno objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Alumno
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
