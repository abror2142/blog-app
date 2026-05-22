<?php

namespace WebDev\BlogBundle\Repository;

use WebDev\BlogBundle\DTO\BlogFilter;
use WebDev\BlogBundle\Entity\Blog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Blog>
 */
class BlogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Blog::class);
    }

    public function save(Blog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Blog $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findFilteredBlogs(BlogFilter $filter): array
    {
        $qb = $this->createFilteredQueryBuilder($filter);
        return $qb->getQuery()->getResult();
    }

    public function slugExists(string $slug, ?int $excludeId = null): bool
    {
        $qb = $this->createQueryBuilder('b')
            ->select('COUNT(b.id)')
            ->where('b.slug = :slug')
            ->setParameter('slug', $slug);
        if (null !== $excludeId) {
            $qb->andWhere('b.id != :excludeId')
                ->setParameter('excludeId', $excludeId);
        }
        return (bool) $qb->getQuery()->getSingleScalarResult();
    }

    private function createFilteredQueryBuilder(BlogFilter $filter): \Doctrine\ORM\QueryBuilder
    {
        $qb = $this->createQueryBuilder('p');

        if (null !== $filter->title && '' !== trim($filter->title)) {
            $qb->andWhere('p.title LIKE :title')
                ->setParameter('title', '%' . addcslashes($filter->title, '%_') . '%');
        }

        if (null !== $filter->status) {
            $qb->andWhere('p.status = :status')
                ->setParameter('status', $filter->status);
        }

        return $qb;
    }
}
