<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 12/11/18
 * Time: 15:34
 */

namespace App\Article;


use App\Entity\Article;

class ArticleFactory
{
    /**
     * Création d'un Objet Article à partir d'un Article Request
     * Pour insertion en DB
     * @param ArticleRequest $request
     * @return Article
     */
    public function createFromArticleRequest(ArticleRequest $request): Article
    {
        return new Article(
            $request->getId(),
            $request->getTitre(),
            $request->getSlug(),
            $request->getContenu(),
            $request->getFeaturedImage(),
            $request->getSpecial(),
            $request->getSpotlight(),
            $request->getCategorie(),
            $request->getMembre(),
            $request->getDateCreation()
        );
    }




}