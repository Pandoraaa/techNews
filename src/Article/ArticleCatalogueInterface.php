<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 19/11/18
 * Time: 16:54
 */

namespace App\Article;


interface ArticleCatalogueInterface extends ArticleRepositoryInterface
{

    public function addSource(ArticleAbstractSource $source): void;
    public function setSources(iterable $sources): void;
    public function getSources(): iterable;

}