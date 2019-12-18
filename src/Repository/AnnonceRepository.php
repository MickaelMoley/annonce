<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
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
     * Récupère les annonces en rapport à la recherche
     * @param SearchData $searchData
     * @return PaginationInterface
     */

    public function findSearch(SearchData $searchData): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('p')
            ->select('p');

        if(!empty($searchData->q))
        {
            $query = $query
                ->andWhere('p.title LIKE :q')
                ->setParameter('q', "%$searchData->q%");
        }

        if(!empty($searchData->make))
        {
            $query = $query
                ->andWhere('p.make LIKE :make')
                ->setParameter('make', "$searchData->make");
        }
        if(!empty($searchData->model))
        {
            $query = $query
                ->andWhere('p.model LIKE :model')
                ->setParameter('model', "$searchData->model");
        }

        if(!empty($searchData->minPrice))
        {
            $query = $query
                ->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $searchData->minPrice);
        }
        if(!empty($searchData->maxPrice))
        {
            $query = $query
                ->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $searchData->maxPrice);
        }
        if(!empty($searchData->minYear))
        {
            $query = $query
                ->andWhere('p.year >= :minYear')
                ->setParameter('minYear', $searchData->minYear);
        }
        if(!empty($searchData->maxYear))
        {
            $query = $query
                ->andWhere('p.year <= :maxYear')
                ->setParameter('maxYear', $searchData->maxYear);
        }



        $query = $query->getQuery();

        return $this->paginator->paginate(
            $query,
            $searchData->current_page,
            $searchData->limitPerPage
        );
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
