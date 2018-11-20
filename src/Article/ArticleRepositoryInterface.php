<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 19/11/18
 * Time: 17:04
 */

namespace App\Article;



use App\Entity\Article;

interface ArticleRepositoryInterface
{
    /**
     * Permet de retourner un Article grace à son identifiant unique
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article;

    /**
     * Récupérer la liste de tous les Articles
     * @return iterable|null
     */
    public function findAll(): ?iterable;

    /**
     * Retourne les derniers Articles
     * @return iterable|null
     */
    public function findLatestArticles(): ?iterable;

    /**
     * Retourne le nombre d'Articles de chaque source pour faire des stats
     * @return int
     */
    public function count(): int;

    public function findSpotlight(): ?iterable;
    public function findSpecial(): ?iterable;

}