<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 12/11/18
 * Time: 15:34
 */

namespace App\Article;


use App\Controller\HelperTrait;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Membre;
use App\Membre\MembreFactory;
use Doctrine\ORM\EntityManagerInterface;

class ArticleFactory
{
    private $em;
    private $membreFactory;

    public function __construct(EntityManagerInterface $manager, MembreFactory $membreFactory)
    {
        $this->em = $manager;
        $this->membreFactory = $membreFactory;
    }

    use HelperTrait;
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

    public function createFromYaml(Array $data): Article
    {
        $article = new Article(
            $data['id'],
            $data['titre'],
            $this->slugify($data['titre']),
            $data['contenu'],
            $data['featuredimage'],
            $data['special'],
            $data['spotlight'],
            // TODO Vérfier l'existence de la catégorie ou la créer ? Question de la persistance ou non en DB
            $this->em->getRepository(Categorie::class)->findOneByNom($data['categorie']['libelle']),
            // TODO Vérfier l'existence du membre -> le créer s'il faut et persister
            $this->em->getRepository(Membre::class)->findOneByEmail($data['auteur']['email']),
            (new \DateTime())->setTimestamp($data['datecreation'])
            );


        if (null === $article->getMembre()) {
            $membre = $this->membreFactory->createFromYaml($data);
            $this->em->persist($membre);
            $this->em->flush();

            $article->setMembre($membre);
        }
        $article->getMembre()->setArticles($article);

        return $article;
    }
}