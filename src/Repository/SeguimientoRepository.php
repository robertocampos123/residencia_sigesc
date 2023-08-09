<?php

namespace App\Repository;

use App\Entity\Seguimiento;
use App\Entity\Tema;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Seguimiento>
 *
 * @method Seguimiento|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seguimiento|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seguimiento[]    findAll()
 * @method Seguimiento[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeguimientoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seguimiento::class);
    }

    public function save(Seguimiento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Seguimiento $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    //public function BuscarSeguimiento()
    //{
    //    return $this->getEntityManager()
    //    ->createQuery('SELECT t.numero_unidad, t.nombre_unidad, t.subtemas,
    //    t.grupo_id, t.parcial_id,
    //    s.fecha_prog_inicio, s.fecha_prog_fin, s.evaluacion_real,
    //    s.fecha_real_inicio, s.fecha_real_fin, s.evaluacion_programada,
    //    s.observaciones, s.evidencia
    //    FROM Seguimiento:class s INNER JOIN Tema:class t
    //    ON s.tema_id = t.tema_id'
    //    )->getResult();
    //}
    
    public function BuscarSeguimientosGRAFJA1($carreras): array
    {
        return $this->createQueryBuilder('s')
                    ->innerJoin('s.grupo', 'g')
                    ->innerJoin('g.docente', 'doc')
                    ->innerJoin('g.materia', 'mat')
                    ->innerJoin('g.carrera', 'car')
                    ->select('g.id, g.clave_grupo, g.inscritos, g.instrumentacion_didactica, doc.nombre_empleado, doc.apellido_paterno,
                              mat.nombre_materia, car.clave_carrera, s.porcentaje_aprobacion as porc_aprob')
                    ->orderBy('g.clave_grupo', 'ASC')
                    ->where('g.carrera IN (:carreras) AND s.parcial = 4')
                    ->groupBy('g.clave_grupo')
                    ->setParameter('carreras', $carreras)
                    ->getQuery()
                    ->getResult()
                    ;
    }

    public function BuscarSeguimientosGRAFJA2($carreras): array
    {
        return $this->createQueryBuilder('s')
                    ->innerJoin('s.grupo', 'g')
                    ->innerJoin('g.docente', 'doc')
                    ->innerJoin('g.materia', 'mat')
                    ->innerJoin('g.carrera', 'car')
                    ->select('g.id, g.clave_grupo, g.inscritos, g.instrumentacion_didactica, doc.nombre_empleado, doc.apellido_paterno,
                              mat.nombre_materia, car.clave_carrera, s.porcentaje_aprobacion as porc_aprob')
                    ->orderBy('g.clave_grupo', 'ASC')
                    ->where('g.carrera IN (:carreras) AND s.parcial = 5')
                    ->groupBy('g.clave_grupo')
                    ->setParameter('carreras', $carreras)
                    ->getQuery()
                    ->getResult()
                    ;
    }

    public function BuscarSeguimientosGRAFJA3($carreras): array
    {
        return $this->createQueryBuilder('s')
                    ->innerJoin('s.grupo', 'g')
                    ->innerJoin('g.docente', 'doc')
                    ->innerJoin('g.materia', 'mat')
                    ->innerJoin('g.carrera', 'car')
                    ->select('g.id, g.clave_grupo, g.inscritos, g.instrumentacion_didactica, doc.nombre_empleado, doc.apellido_paterno,
                              mat.nombre_materia, car.clave_carrera, s.porcentaje_aprobacion as porc_aprob')
                    ->orderBy('g.clave_grupo', 'ASC')
                    ->where('g.carrera IN (:carreras) AND s.parcial = 6')
                    ->groupBy('g.clave_grupo')
                    ->setParameter('carreras', $carreras)
                    ->getQuery()
                    ->getResult()
                    ;
    }



    public function BuscarSeguimientoJFPar1($grupo): array
        {
            return $this->createQueryBuilder('s')
                        ->select('s')
                        ->innerJoin('App\Entity\Tema', 't','WITH','s.tema = t.id')
                        ->orderBy('t.numero_unidad', 'ASC')
                        ->where('s.grupo = :grupo AND s.parcial = 4')
                        ->setParameter('grupo', $grupo)
                        ->getQuery()
                        ->getResult()
                        ;
        }

        public function BuscarSeguimientoJFPar2($grupo): array
        {
            return $this->createQueryBuilder('s')
                        ->select('s')
                        ->innerJoin('App\Entity\Tema', 't','WITH','s.tema = t.id')
                        ->orderBy('t.numero_unidad', 'ASC')
                        ->where('s.grupo = :grupo AND s.parcial = 5')
                        ->setParameter('grupo', $grupo)
                        ->getQuery()
                        ->getResult()
                        ;
        }

        public function BuscarSeguimientoJFPar3($grupo): array
        {
            return $this->createQueryBuilder('s')
                        ->select('s')
                        ->innerJoin('App\Entity\Tema', 't','WITH','s.tema = t.id')
                        ->orderBy('t.numero_unidad', 'ASC')
                        ->where('s.grupo = :grupo AND s.parcial = 6')
                        ->setParameter('grupo', $grupo)
                        ->getQuery()
                        ->getResult()
                        ;
        }

        public function BuscarSeguimientos($grupo): array
        {
            return $this->createQueryBuilder('s')
                        ->select('s')
                        ->innerJoin('App\Entity\Tema', 't','WITH','s.tema = t.id')
                        ->orderBy('t.numero_unidad', 'ASC')
                        ->where('s.grupo = :grupo')
                        ->setParameter('grupo', $grupo)
                        ->getQuery()
                        ->getResult()
                        ;
        }

        public function BuscarSeguimientoPar1($grupo): array
        {
            return $this->createQueryBuilder('s')
                        ->select('s')
                        ->innerJoin('App\Entity\Tema', 't','WITH','s.tema = t.id')
                        ->orderBy('t.numero_unidad', 'ASC')
                        ->where('s.grupo = :grupo AND s.parcial = 4')
                        ->setParameter('grupo', $grupo)
                        ->getQuery()
                        ->getResult()
                        ;
        }

        public function BuscarSeguimientoPar2($grupo): array
        {
            return $this->createQueryBuilder('s')
                        ->select('s')
                        ->innerJoin('App\Entity\Tema', 't','WITH','s.tema = t.id')
                        ->orderBy('t.numero_unidad', 'ASC')
                        ->where('s.grupo = :grupo AND s.parcial = 5')
                        ->setParameter('grupo', $grupo)
                        ->getQuery()
                        ->getResult()
                        ;
        }

        public function BuscarSeguimientoPar3($grupo): array
        {
            return $this->createQueryBuilder('s')
                        ->select('s')
                        ->innerJoin('App\Entity\Tema', 't','WITH','s.tema = t.id')
                        ->orderBy('t.numero_unidad', 'ASC')
                        ->where('s.grupo = :grupo AND s.parcial = 6')
                        ->setParameter('grupo', $grupo)
                        ->getQuery()
                        ->getResult()
                        ;
        }

        

//    /** 
//     * @return Seguimiento[] Returns an array of Seguimiento objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Seguimiento
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
