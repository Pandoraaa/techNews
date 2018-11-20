<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 19/11/18
 * Time: 17:08
 */

namespace App\Article\Source;


use App\Article\ArticleAbstractSource;
use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;

class DoctrineSource extends ArticleAbstractSource
{
    private $repository;
    private $entity = Article::class;

    public function __construct(ObjectManager $manager)
    {
        $this->repository = $manager->getRepository($this->entity);
    }

    /**
     * Permet de convertir un tableau en Article.
     * @param iterable $article
     * @return Article|null
     */
    protected function createArticleFromArray(iterable $article): ?Article
    {
        return null;
    }

    /**
     * Permet de retourne un Article grace à son identifiant unique.
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article
    {
        return $this->repository->find($id);
    }

    /**
     * Récupérer la liste de tous les Articles.
     * @return iterable|null
     */
    public function findAll(): ?iterable
    {
        return $this->repository->findAll();
    }

    /**
     * Récupère les derniers Articles
     * @return iterable|null
     */
    public function findLatestArticles(): ?iterable
    {
        return $this->repository->findLastestArticles();
    }

    /**
     * @return iterable|null
     */
    public function findSpotlight(): ?iterable
    {
        return $this->repository->findSpotlightArticles();
    }

    /**
     * @return iterable|null
     */
    public function findSpecial(): ?iterable
    {
        return $this->repository->findSpecialArticles();
    }

    /**
     * Retourne le nombre d'Articles de chaque source pour faire des stats
     * @return int
     */
    public function count(): int
    {
        $this->repository->countArticles();
    }


}