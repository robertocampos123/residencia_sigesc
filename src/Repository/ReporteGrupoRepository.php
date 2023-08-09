<?php

namespace App\Repository;

use App\Entity\ReporteGrupo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReporteGrupo>
 *
 * @method ReporteGrupo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReporteGrupo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReporteGrupo[]    findAll()
 * @method ReporteGrupo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReporteGrupoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReporteGrupo::class);
    }

    public function save(ReporteGrupo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ReporteGrupo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function sumaEv($docente): array
        {
            return $this->createQueryBuilder('r')
                        ->innerJoin('r.grupo', 'gpo')
                        ->innerJoin('gpo.materia', 'mat')
                        ->innerJoin('gpo.carrera', 'car')
                        ->select('mat.nombre_materia, car.nombre_carrera, 
                                  SUM(gpo.inscritos) as suma_ins, SUM(r.ac_ordinario) as suma_ord, 
                                  SUM(r.ac_regularizacion) as suma_reg, SUM(r.ac_extraordinario) as suma_ex,
                                  SUM(r.no_acreditado) as suma_no_ac')
                        ->where('gpo.docente = :docente')
                        ->setParameter('docente', $docente)
                        ->groupBy('gpo.materia, gpo.carrera')
                        ->getQuery()
                        ->getResult();
        }


        public function reporteGrup($docente): array
        {
            return $this->createQueryBuilder('r')
                        ->innerJoin('r.grupo', 'gpo')
                        ->innerJoin('gpo.materia', 'mat')
                        ->innerJoin('gpo.carrera', 'car')
                        ->select('gpo.id, gpo.clave_grupo, mat.nombre_materia, car.nombre_carrera, gpo.inscritos, 
                                  r.ac_ordinario, r.ac_regularizacion, r.ac_extraordinario, r.no_acreditado, r.desertados')
                        ->where('gpo.docente = :docente')
                        ->setParameter('docente', $docente)
                        ->orderBy('gpo.clave_grupo','ASC')
                        ->getQuery()
                        ->getResult();
        }



//    /**
//     * @return ReporteGrupo[] Returns an array of ReporteGrupo objects
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

//    public function findOneBySomeField($value): ?ReporteGrupo
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
