<?php

namespace App\Repository;

use App\Entity\Comments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    /**
     * Compte le nombre de commentaires pour un post spécifique avec une note spécifique
     */
    // Dans CommentsRepository
    public function getRatingStats(int $postId): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.post = :postId')
            ->setParameter('postId', $postId)
            ->orderBy('c.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

}