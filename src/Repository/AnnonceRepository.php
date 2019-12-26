<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Annonce::class);
        $this->paginator = $paginator;
    }

    /**
     * Récupère la liste des annonces
     * @param SearchData $searchData
     * @param bool $ignorePrice
     * @return QueryBuilder
     */
    private function getSearchQuery(SearchData $searchData, $ignoreFilter = false): QueryBuilder
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p');

        if(!empty($searchData->q))
        {
            $query = $query
                ->andWhere('p.title LIKE :q
                 OR p.description LIKE :q 
                 OR p.features LIKE :q 
                 OR p.make LIKE :q 
                 OR p.model LIKE :q 
                 OR p.vehicle_id LIKE :q 
                 OR p.year LIKE :q 
                 OR p.adress LIKE :q 
                 OR p.fuel_type LIKE :q')
                ->setParameter('q', "%$searchData->q%");
        }

        if(!empty($searchData->make))
        {
            $query = $query
                ->andWhere('p.make LIKE :make')
                ->setParameter('make', "%$searchData->make%");
        }
        if(!empty($searchData->model))
        {
            $query = $query
                ->andWhere('p.model LIKE :model')
                ->setParameter('model', "%$searchData->model%");
        }

        if(!empty($searchData->bodyStyle))
        {
            $query = $query
                ->andWhere('p.body_style LIKE :bodyStyle')
                ->setParameter('bodyStyle', "%$searchData->bodyStyle%");
        }

        if(!empty($searchData->fuelType))
        {
            $query = $query
                ->andWhere('p.fuel_type LIKE :fuelType')
                ->setParameter('fuelType', "%$searchData->fuelType%");
        }
        if(!empty($searchData->transmission))
        {
            $query = $query
                ->andWhere('p.transmission LIKE :transmission')
                ->setParameter('transmission', "%$searchData->transmission%");
        }


        if(!empty($searchData->minPrice) && $ignoreFilter === false)
        {
            $query = $query
                ->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $searchData->minPrice);
        }
        if(!empty($searchData->maxPrice) && $ignoreFilter === false)
        {
            $query = $query
                ->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $searchData->maxPrice);
        }
        if(!empty($searchData->minYear) && $ignoreFilter === false)
        {
            $query = $query
                ->andWhere('p.year >= :minYear')
                ->setParameter('minYear', $searchData->minYear);
        }
        if(!empty($searchData->maxYear) && $ignoreFilter === false)
        {
            $query = $query
                ->andWhere('p.year <= :maxYear')
                ->setParameter('maxYear', $searchData->maxYear);
        }

        return $query;
    }

    /**
     * Récupère les annonces par rapport au filtre
     * @param SearchData $searchData
     * @return PaginationInterface
     */

    public function findSearch(SearchData $searchData): PaginationInterface
    {

        $query = $this->getSearchQuery($searchData)->getQuery();

        return $this->paginator->paginate(
            $query,
            $searchData->current_page,
            $searchData->limitPerPage
        );
    }

    /**
     * Récupère le prix minimun et maximum parmis la liste des annonces
     * @return integer[]
     */
    public function findMinMaxPrice(SearchData $searchData): array
    {
        $resultat = $this->getSearchQuery($searchData, true)
            ->select('MIN(p.price) as min', 'MAX(p.price) as max')
            ->getQuery()
            ->getScalarResult();


        return [$resultat[0]['min'], $resultat[0]['max']];
    }

    /**
     * Récupère le date minimun et maximum parmis la liste des annonces
     * @return integer[]
     */
    public function findMinMaxYear(SearchData $searchData): array
    {
        $resultat = $this->getSearchQuery($searchData, true)
            ->select('MIN(p.year) as min', 'MAX(p.year) as max')
            ->getQuery()
            ->getScalarResult();
        return [$resultat[0]['min'], $resultat[0]['max']];
    }


    /**
     * Récupère la liste unique des marques
     * @return integer[]
     */
    public function findMake(): array
    {
        $resultat = $this->createQueryBuilder('ma')
            ->select('ma.make')
            ->orderBy('ma.make', 'ASC')
            ->distinct(true)
            ->getQuery()
            ->getArrayResult()
            ;

        $re = [];
        foreach ($resultat as $item)
        {
            foreach ($item as $make)
            {
                array_push($re, $make);
            }
        }
        return $re;
    }

    /**
     * Récupère la liste unique des marques
     * @return integer[]
     */
    public function findModel(): array
    {
        $resultat = $this->createQueryBuilder('mo')
            ->select('mo.model')
            ->orderBy('mo.model', 'ASC')
            ->distinct(true)
            ->getQuery()
            ->getArrayResult()
        ;
        $re = [];
        foreach ($resultat as $item)
        {
            foreach ($item as $model)
            {
                array_push($re, $model);
            }
        }

        return $re;
    }

    /**
     * Récupère la liste unique des catégories de voitures
     * @return integer[]
     */
    public function findBodyStyle(): array
    {
        $resultat = $this->createQueryBuilder('bs')
            ->select('bs.body_style')
            ->orderBy('bs.body_style', 'ASC')
            ->distinct(true)
            ->getQuery()
            ->getArrayResult()
        ;
        $re = [];
        foreach ($resultat as $item)
        {
            foreach ($item as $bodyStyle)
            {
                array_push($re, $bodyStyle);
            }
        }

        return $re;
    }





    // /**
    //  * @return Annonce[] Returns an array of Annonce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Annonce
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
