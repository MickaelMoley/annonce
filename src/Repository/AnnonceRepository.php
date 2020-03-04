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
     * @param bool $ignoreFilter
     * @return QueryBuilder
     */
    private function getSearchQuery(SearchData $searchData, $ignoreFilter = false): QueryBuilder
    {
        dump($searchData);
        $query = $this
            ->createQueryBuilder('p')
            ->select('p');
        if (!empty($searchData->q)) {
            $query = $query
                ->innerJoin('p.dealer', 'dealer')
                ->andWhere('p.title LIKE :q
                 OR p.description LIKE :q 
                 OR p.features LIKE :q 
                 OR p.make LIKE :q 
                 OR p.model LIKE :q 
                 OR p.vehicle_id LIKE :q 
                 OR p.year LIKE :q 
                 OR p.adress LIKE :q 
                 OR p.fuel_type LIKE :q
                 OR p.mileage LIKE :q
                 OR p.price LIKE :q
                 OR dealer.dealer_name LIKE :q')
                ->setParameter('q', "%$searchData->q%");
        }

        if (!empty($searchData->make)) {
            $query = $query
                ->andWhere('p.make LIKE :make')
                ->setParameter('make', "%$searchData->make%");
        }
        if (!empty($searchData->model)) {
            $query = $query
                ->andWhere('p.model LIKE :model')
                ->setParameter('model', "%$searchData->model%");
        }
        if (!empty($searchData->bodyStyle)) {
            $query = $query
                ->andWhere('p.body_style LIKE :bodyStyle')
                ->setParameter('bodyStyle', "%$searchData->bodyStyle%");
        }
        if (!empty($searchData->fuelType)) {
            $query = $query
                ->andWhere('p.fuel_type LIKE :fuelType')
                ->setParameter('fuelType', "%$searchData->fuelType%");
        }

        if (!empty($searchData->transmission)) {
            $query = $query
                ->andWhere('p.transmission LIKE :transmission')
                ->setParameter('transmission', "%$searchData->transmission%");
        }
        if (!empty($searchData->minPrice) && $ignoreFilter === false) {
            $query = $query
                ->andWhere('p.price >= :minPrice')
                ->setParameter('minPrice', $searchData->minPrice);
        }
        if (!empty($searchData->maxPrice) && $ignoreFilter === false) {
            $query = $query
                ->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $searchData->maxPrice);
        }
        if (!empty($searchData->minYear) && $ignoreFilter === false) {
            $query = $query
                ->andWhere('p.year >= :minYear')
                ->setParameter('minYear', $searchData->minYear);
        }
        if (!empty($searchData->maxYear) && $ignoreFilter === false) {
            $query = $query
                ->andWhere('p.year <= :maxYear')
                ->setParameter('maxYear', $searchData->maxYear);
        }

     if(!empty($searchData->minMileage) && $ignoreFilter === false){
            $query = $query
                ->andWhere('p.mileage >= :minMileage')
                ->setParameter('minMileage', $searchData->minMileage);
        }


        if(!empty($searchData->maxMileage && $ignoreFilter === false)){
            $query = $query
                ->andWhere('p.mileage <= :maxMileage')
                ->setParameter('maxMileage', $searchData->maxMileage);
        }

        if (!empty($searchData->dealer_id)) {
            $query = $query
                ->andWhere('p.dealer_ref = :dealerId')
                ->setParameter('dealerId', $searchData->dealer_id);
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
     * @param SearchData $searchData
     * @return integer[]
     */
    public function findMinMaxPrice(SearchData $searchData): array
    {
        $result = $this->getSearchQuery($searchData, true)
            ->select('MIN(p.price) as min', 'MAX(p.price) as max')
            ->getQuery()
            ->getScalarResult();
        return [$result[0]['min'], $result[0]['max']];
    }

    /**
     * Récupère le kilometre minimun et maximum parmis la liste des annonces
     * @param SearchData $searchData
     * @return integer[]
     */
    public function findMinMaxKilometer(SearchData $searchData): array
    {
        $result = $this->getSearchQuery($searchData, true)
            ->select('MIN(p.mileage) as min', 'MAX(p.mileage) as max')
            ->getQuery()
            ->getScalarResult();
        dump($result);
        return [$result[0]['min'], $result[0]['max']];
    }

    /**
     * Récupère le date minimun et maximum parmis la liste des annonces
     * @param SearchData $searchData
     * @return integer[]
     */
    public function findMinMaxYear(SearchData $searchData): array
    {
        $result = $this->getSearchQuery($searchData, true)
            ->select('MIN(p.year) as min', 'MAX(p.year) as max')
            ->getQuery()
            ->getScalarResult();
        return [$result[0]['min'], $result[0]['max']];
    }

    /**
     * Récupère la liste unique des marques
     * @param string $dealer
     * @return integer[]
     */
    public function findMake($dealer = null): array
    {
        $result = $this->createQueryBuilder('ma')
            ->select('ma.make');
        if ($dealer) {
            $result = $result
                ->setParameter(':dealerId', $dealer)
                ->andwhere('ma.dealer_ref = :dealerId');
        }
        $result = $result
            ->orderBy('ma.make', 'ASC')
            ->distinct(true)
            ->getQuery()
            ->getArrayResult();
        $re = [];
        foreach ($result as $item) {
            foreach ($item as $make) {
                array_push($re, $make);
            }
        }
        return $re;
    }

    /**
     * Récupère la liste unique des modèles
     * @param string $dealer
     * @return integer[]
     */
    public function findModel($dealer = null): array
    {
        $result = $this->createQueryBuilder('mo')
            ->select('mo.model');
        if ($dealer) {
            $result = $result
                ->setParameter(':dealerId', $dealer)
                ->andwhere('mo.dealer_ref = :dealerId');
        }
        $result = $result
            ->orderBy('mo.model', 'ASC')
            ->distinct(true)
            ->getQuery()
            ->getArrayResult();
        $re = [];
        foreach ($result as $item) {
            foreach ($item as $model) {
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
        $result = $this->createQueryBuilder('bs')
            ->select('bs.body_style')
            ->orderBy('bs.body_style', 'ASC')
            ->distinct(true)
            ->getQuery()
            ->getArrayResult();
        $re = [];
        foreach ($result as $item) {
            foreach ($item as $bodyStyle) {
                array_push($re, $bodyStyle);
            }
        }
        return $re;
    }

    /**
     * Récupère la liste unique des types de carburant
     * @return integer[]
     */
    public function findCarburant(): array
    {
        $result = $this->createQueryBuilder('ft')
            ->select('ft.fuel_type')
            ->orderBy('ft.fuel_type', 'ASC')
            ->distinct(true)
            ->getQuery()
            ->getArrayResult();
        $re = [];
        foreach ($result as $item) {
            foreach ($item as $fuelType) {
                array_push($re, $fuelType);
            }
        }
        return $re;
    }

    public function findTransmission(): array
    {
        $result = $this->createQueryBuilder('t')
            ->select('t.transmission')
            ->orderBy('t.transmission', 'ASC')
            ->distinct(true)
            ->getQuery()
            ->getArrayResult();
        $re = [];
        foreach ($result as $item) {
            foreach ($item as $transmission) {
                array_push($re, $transmission);
            }
        }
        return $re;
    }

    /**
     * @param $make
     * @param null $dealer
     * @return array []
     */
    public function findModelByMake($make, $dealer = null)
    {
        $result = $this->createQueryBuilder('a')
            ->select('a.model')
            ->setParameter(':make', $make)
            ->where('a.make = :make');
        if ($dealer) {
            $result = $result
                ->setParameter(':dealerId', $dealer)
                ->andWhere('a.dealer_ref = :dealerId');
        }
        return $result
            ->distinct(true)
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param $value
     * @return array []
     */
    public function getAnnoncebyDealer($dealer)
    {
        return $this->createQueryBuilder('a')
            ->setParameter(':dealer', $dealer)
            ->where('a.dealer_id = :dealer')
            ->distinct(true)
            ->getQuery()
            ->getArrayResult();
    }
}