<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 19/11/18
 * Time: 17:07
 */

namespace App\Article\Source;


use App\Article\ArticleAbstractSource;
use App\Article\Provider\YamlProvider;
use App\Controller\HelperTrait;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Membre;
use Tightenco\Collect\Support\Collection;

class YamlSource extends ArticleAbstractSource
{
    use HelperTrait;
    private $articles = [];

    public function __construct(YamlProvider $yamlProvider)
    {
        $this->articles = new Collection($yamlProvider->getArticles());

        # On commente pour ne convertir que quand on en a besoin
//        foreach ($this->articles as &$article) {
//            $article = $this->createArticleFromArray($article);
//        }
    }

    /**
     * Permet de convertir un tableau en Article
     * @param iterable $article
     * @return Article|null
     */
    protected function createArticleFromArray(iterable $article): ?Article
    {
        $tmp = (object)$article;

        //FIXME attention aux id
        # Construire l'objet Categorie
        $categorie = new Categorie();
        $categorie->setId($tmp->categorie['id']);
        $categorie->setNom($tmp->categorie['libelle']);
        $categorie->setSlug($this->slugify($tmp->categorie['libelle']));

        # Construire l'objet Membre
        $membre = new Membre();
        $membre->setId($tmp->auteur['id']);
        $membre->setNom($tmp->auteur['nom']);
        $membre->setPrenom($tmp->auteur['prenom']);
        $membre->setEmail($tmp->auteur['email']);
        $membre->setRoles(['ROLE_AUTEUR']);
        $membre->setDateInscription(new \DateTime());

        # Construire l'objet Article
        $date = new \DateTime();
        return new Article(
            $tmp->id,
            $tmp->titre,
            $this->slugify($tmp->titre),
            $tmp->contenu,
            $tmp->featuredimage,
            $tmp->special,
            $tmp->spotlight,
            $categorie,
            $membre,
            $date->setTimestamp($tmp->datecreation)
        );
    }

    /**
     * @param $id
     * @return Article|null
     */
    public function find($id): ?Article
    {
       $article = $this->articles->firstWhere('id', $id);
       return $article == null ? null : $this->createArticleFromArray($article);
    }

    /**
     * @return iterable|null
     */
    public function findAll(): ?iterable
    {
        $articles = new Collection();
        foreach ($this->articles as $article) {
            $articles[] = $this->createArticleFromArray($article);
        }

        return $articles;
    }

    /**
     * @return iterable|null
     */
    public function findLatestArticles(): ?iterable
    {
        /** @var Collection $articles */
        $articles = $this->findAll();
        return $articles->sortByDesc(function($col) {
            return $col->getDateCreation();
        })->slice(-5);

    }

    public function findSpotlight(): ?iterable
    {
        $results = [];
        $articles = $this->findAll();
        foreach ($articles as $article) {
            if ($article->getSpotlight() == 1) {
                $results[] = $article;
            }
        }
        return $results;
    }

    public function findSpecial(): ?iterable
    {
        $results = [];
        $articles = $this->findAll();
        foreach ($articles as $article) {
            if ($article->getSpecial() == 1) {
                $results[] = $article;
            }
        }
        return $results;
    }

    /**
     * Retourne le nombre d'Articles de chaque source pour faire des stats
     * @return int
     */
    public function count(): int
    {
        return $this->articles->count();
    }


}