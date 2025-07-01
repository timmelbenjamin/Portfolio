<?php

namespace App\Repository;

use App\Entity\Posts;
use App\Entity\PostsSearch;
use App\Service\GoogleApiService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Posts>
 */
class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

    public function findPostsQuery()
    {
        return $this->createQueryBuilder('p');
    }

    public function findFilteredQuery(PostsSearch $search, ?array $cityList = null)
    {
        $query = $this->findPostsQuery();

        if ($search->getActivities()) {
            $query->andWhere('p.activity = :activity')
                  ->setParameter('activity', $search->getActivities());
        }

        if ($cityList) {
            $query->andWhere('p.city IN (:city)')
                  ->setParameter('city', $cityList);
        }
        
        $query->andWhere('p.is_certified = :certified')
        ->setParameter('certified', 1); 

        return $query->getQuery()->getResult();
    }

    //    /**
    //     * @return Posts[] Returns an array of Posts objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Posts
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
