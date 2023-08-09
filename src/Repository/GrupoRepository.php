<?php

namespace App\Repository;

use App\Entity\Grupo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Grupo>
 *
 * @method Grupo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Grupo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Grupo[]    findAll()
 * @method Grupo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GrupoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Grupo::class);
    }

    public function save(Grupo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Grupo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function numeroGrupos($docente)
        {
            return $this->createQueryBuilder('g')
                        ->where('g.docente = :docente')
                        ->setParameter('docente', $docente)
                        ->select('COUNT(g.id) as grupos')
                        ->getQuery()
                        ->getSingleScalarResult();
                        ;
        }

        public function materiasImpartidas($docente):array
        {
            return $this->createQueryBuilder('g')
                        ->where('g.docente = :docente')
                        ->setParameter('docente', $docente)
                        ->select('COUNT(g.id) as grupos')
                        ->groupBy('g.materia')
                        ->getQuery()
                        ->getResult();
        }

        public function sumaGrupos($docente): array
        {
            return $this->createQueryBuilder('g')
                        ->innerJoin('g.materia', 'mat')
                        ->innerJoin('g.carrera', 'car')
                        ->select('SUM(g.inscritos) as suma, mat.nombre_materia, car.nombre_carrera ')
                        ->where('g.docente = :docente')
                        ->setParameter('docente', $docente)
                        ->groupBy('g.materia, g.carrera')
                        ->getQuery()
                        ->getResult();
        }

        public function noAlumnos($grupo)
        {
            return $this->createQueryBuilder('g')
                        ->select('SIZE(g.alumnos)')
                        ->where('g.id = :grupo')
                        ->setParameter('grupo', $grupo)
                        ->getQuery()
                        ->getSingleScalarResult();
                        
        }

        public function BuscarSeguimientosJA1($carreras): array
        {
            return $this->createQueryBuilder('g')
                        ->innerJoin('g.docente', 'doc')
                        ->innerJoin('g.materia', 'mat')
                        ->innerJoin('g.carrera', 'car')
                        ->select('g.id, g.clave_grupo, g.inscritos, g.instrumentacion_didactica, doc.nombre_empleado, doc.apellido_paterno,
                                  mat.nombre_materia, car.clave_carrera')
                        ->orderBy('g.clave_grupo', 'ASC')
                        ->where('g.carrera IN (:carreras)')
                        ->setParameter('carreras', $carreras)
                        ->getQuery()
                        ->getResult()
                        ;
        }

        public function query_seguimiento_grupos($carreras)
        {
            return $this->getEntityManager()
                        ->createQuery('SELECT g.id, g.clave_grupo, g.inscritos, doc.nombre_empleado, doc.apellido_paterno,
                                              mat.nombre_materia, car.clave_carrera
                                              FROM App:Grupo g
                                              JOIN g.docente doc
                                              JOIN g.materia mat
                                              JOIN g.carrera car
                                              WHERE g.carrera 
                                              IN (:carreras)
                                              ')
                                              ->setParameter('carreras', $carreras);
                    
        }
        
        public function BuscarSeguimientosGrupoJF1($carreras): array
        {
            return $this->createQueryBuilder('g')
                        ->innerJoin('g.docente', 'doc')
                        ->innerJoin('g.materia', 'mat')
                        ->innerJoin('g.carrera', 'car')
                        ->innerJoin('g.seguimientos', 's')
                        ->select('g.id, g.clave_grupo, g.inscritos, g.instrumentacion_didactica, doc.nombre_empleado, doc.apellido_paterno,
                                  mat.nombre_materia, car.clave_carrera, s.porcentaje_aprobacion as porc_aprob')
                        ->orderBy('g.clave_grupo', 'ASC')
                        ->where('g.carrera IN (:carreras) AND s.parcial = 4')
                        ->setParameter('carreras', $carreras)
                        ->getQuery()
                        ->getResult()
                        ;
        }

     

    public function queryMenu($empleado)
    {
        return $this->getEntityManager()
                    ->createQuery(
                    'SELECT g.id, g.clave_grupo, g.horario, g.inscritos, g.aula,
                    car.nombre_carrera as carrera, mat.nombre_materia as nombre_mat
                    FROM App:Grupo g
                    JOIN g.carrera car
                    JOIN g.materia mat
                    WHERE g.docente = :empleado
                    ORDER BY g.clave_grupo ASC'
                    )
                    ->setParameter('empleado',$empleado);
                    
    }

    public function query_seguimiento_instrumentacion($carreras)
    {
        return $this->getEntityManager()
                    ->createQuery('SELECT g.id, g.clave_grupo, g.inscritos, g.instrumentacion_didactica, doc.nombre_empleado, doc.apellido_paterno,
                     mat.nombre_materia, car.clave_carrera
                     FROM App:Grupo g
                     JOIN g.docente doc
                     JOIN g.materia mat 
                     JOIN g.carrera car
                     WHERE g.carrera 
                     IN (:carreras)
                     ORDER BY g.clave_grupo ASC')
                    ->setParameter('carreras', $carreras);
    }



    public function todosGrupo()
    {
        return $this->getEntityManager()
                    ->createQuery(
                    'SELECT g.id, g.clave_grupo, g.inscritos, g.aula, g.horario, 
                    doc.RFC as rfc, mat.nombre_materia as nombre_mat
                    FROM App:Grupo g
                    JOIN g.materia mat
                    JOIN g.docente doc
                    ORDER BY g.clave_grupo ASC'
                    );
    }



//SELECT materia_id, SUM(inscritos) FROM `grupo` WHERE docente_id=7 GROUP BY materia_id;

       

//    /**
//     * @return Grupo[] Returns an array of Grupo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Grupo
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
