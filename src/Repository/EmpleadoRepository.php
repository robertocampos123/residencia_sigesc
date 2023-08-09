<?php

namespace App\Repository;

use App\Entity\Empleado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Empleado>
 *
 * @method Empleado|null find($id, $lockMode = null, $lockVersion = null)
 * @method Empleado|null findOneBy(array $criteria, array $orderBy = null)
 * @method Empleado[]    findAll()
 * @method Empleado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpleadoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Empleado::class);
    }

    public function save(Empleado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Empleado $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function todosEmpleados()
    {
        return $this->getEntityManager()
                    ->createQuery(
                    'SELECT e.id, e.RFC, e.nombre_empleado, e.apellido_paterno, e.apellido_materno, e.genero,
                    car.nombre_cargo as cargo, dep.nombre_departamento as departamento
                    FROM App:Empleado e
                    JOIN e.departamento dep
                    JOIN e.cargo car
                    ORDER BY e.apellido_paterno ASC'
                    );
                    
    }

//    /**
//     * @return Empleado[] Returns an array of Empleado objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Empleado
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
