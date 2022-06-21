<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Article $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Article $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // je récupère le dernier article de manière decroisssantes
    /**
     * @return Article[] Returns an array of Article objects
     */

    public function last(int $maxResults, Categorie $category = null)
    {
        $builder = $this->createQueryBuilder('a')
        ->orderBy('a.id', 'DESC')
        ->setMaxResults($maxResults)
        ;

        if(!empty($category)) {
            $builder
                ->innerJoin('a.categorie', 'c')
                ->andWhere('c IN (:category)')
                ->setParameter(':category', $category)
            ;
        }

        return $builder
            ->andWhere('a.published = true')
            ->getQuery()
            ->getResult();
    }

    // je récupère les articles de la catégorie
    /**
     * @return Article[] Returns an array of Article objects
     */
    public function findAllArticle(Categorie $categorie): array
    {
        return $this->createQueryBuilder('p')
            ->where(':categorie MEMBER OF p.categorie')
            ->setParameter('categorie', $categorie)
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();
    }



    /*
    public function findOneBySomeField($value): ?Article
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
