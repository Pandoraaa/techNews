<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    const MAX_RESULT = 5;
    const MAX_SUGGESTIONS = 3;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Récupère les derniers articles
     */
    public function findLastestArticles()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(self::MAX_RESULT)
            ->getQuery()
            ->getResult()
            ;
    }


    public function findArticlesSuggestions($idArticle, $idCategorie)
    {
        # Tous les articles d'une catégorie ($idCategorie)
        return $this->createQueryBuilder('a')
            ->where('a.categorie = :categorie_id')
            ->setParameter('categorie_id', $idCategorie)

            # sauf un article ($idArticle)
            ->andWhere('a.id != :article_id')
            ->setParameter('article_id', $idArticle)

            # ordre décroissant
            ->orderBy('a.id', 'DESC')

            # 3 Articles max
            ->setMaxResults(self::MAX_SUGGESTIONS)

            # On finalise
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Récupérer les articles en Spotlight
     */
    public function findSpotlightArticles()
    {
        return $this->createQueryBuilder('a')
            ->where('a.spotlight = 1')
            ->orderBy('a.id','DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * Récupérer les articles "special" de la sidebar
     */
    public function findSpecialArticles()
    {
        return $this->createQueryBuilder('a')
            ->where('a.special = 1')
            ->orderBy('a.id','DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
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
