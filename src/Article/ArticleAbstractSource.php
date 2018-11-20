<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 19/11/18
 * Time: 16:55
 */

namespace App\Article;


use App\Entity\Article;

abstract class ArticleAbstractSource implements ArticleRepositoryInterface
{
    protected $sourceId;
    /**
     * Permet de convertir un tableau en Article
     * @param iterable $article
     * @return Article|null
     */
    abstract protected function createArticleFromArray(iterable $article): ?Article;

}