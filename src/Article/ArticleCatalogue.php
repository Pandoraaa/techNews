<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 19/11/18
 * Time: 17:05
 */

namespace App\Article;


use App\Entity\Article;
use App\Exception\DuplicateCatalogueArticleException;
use Tightenco\Collect\Support\Collection;

class ArticleCatalogue implements ArticleCatalogueInterface
{
    protected $sources = [];


    public function addSource(ArticleAbstractSource $source): void
    {
        if (!in_array($source, $this->sources)) {
            $this->sources[] = $source;
        }
    }

    public function setSources(iterable $sources): void
    {
        $this->sources = $sources;
    }

    public function getSources(): iterable
    {
        return $this->sources;
    }

    public function find($id): ?Article
    {
        $articles = new Collection();

        /** @var ArticleAbstractSource $source */
        foreach ($this->sources as $source) {
            # J'appelle la méthode find de chaque source
            $article = $source->find($id);

            # Si ma source ne renvoie pas null alors je l'ajoute au tableau
            if (null !== $article) {
                $articles[] = $article;
            }

            # Vérification des doublons
            if ($articles->count()>1) {
                throw new DuplicateCatalogueArticleException(sprintf(
                    'Return value of %s cannot return more than one record on line %s',
                    get_class($this).'::'.__FUNCTION__.'()', __LINE__
                ));
            }
        }

        # Je retourne l'article de la dernière source
        return $articles->pop();
    }

    public function findAll(): ?iterable
    {
        return $this->sourcesIterator('findAll')
            ->sortByDesc(function($col) {
                return $col->getDateCreation();
            });
    }

    public function findLatestArticles(): ?iterable
    {
        return $this->sourcesIterator('findLatestArticles')
            ->sortByDesc(function($col) {
            return $col->getDateCreation();
        })
            ->slice(-5);

    }

    public function findSpotlight(): ?iterable
    {
        return $this->sourcesIterator('findSpotlight')
            ->sortByDesc(function($col) {
                return $col->getDateCreation();
            })
            ->slice(-5);
    }

    public function findSpecial(): ?iterable
    {
        return $this->sourcesIterator('findSpecial')
            ->sortByDesc(function($col) {
                return $col->getDateCreation();
            })
            ->slice(-5);
//        $results = [];
//        foreach ($this->sources as $source) {
//            $specials = $source->findSpecial();
//            foreach ($specials as $special) {
//                $results[] = $special;
//            }
//        }
//        return $results;
    }

    /**
     * Retourne le nombre d'Articles de chaque source pour faire des stats
     * @return int
     */
    public function count(): int
    {
        return count($this->sources);
    }

    /**
     * Parcours les sources
     * @param string $callback
     */
    private function sourcesIterator(string $callback): Collection
    {
        $articles = new Collection();
        /** @var ArticleAbstractSource $source */
        /** @var Article $article */
        foreach ($this->sources as $source) {
            foreach ($source->$callback() as $article) {
                $articles [] = $article;
            }
        }
        return $articles;
    }
}