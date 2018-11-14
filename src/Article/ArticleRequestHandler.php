<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 12/11/18
 * Time: 15:04
 */

namespace App\Article;

use App\Controller\HelperTrait;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleRequestHandler
{
    use HelperTrait;

    private $em, $articleAssetsDir, $articleFactory;

    /**
     * ArticleRequestHandler constructor.
     * @param EntityManagerInterface $entityManager
     * @param ArticleFactory $articleFactory
     * @param string $articleAssetsDir
     */
    public function __construct(EntityManagerInterface $entityManager,
                                ArticleFactory $articleFactory,
                                string $articleAssetsDir)
    {
        $this->em = $entityManager;
        $this->articleFactory = $articleFactory;
        $this->articleAssetsDir = $articleAssetsDir;
    }

    public function handle(ArticleRequest $request): ?Article
    {
        # Traitement de l'upload de l'image  images/product
        /** @var UploadedFile $image */
        $image = $request->getFeaturedImage();

        $fileName = $this->slugify($request->getTitre()).'.'.$image->guessExtension();

        try {
            $image->move(
                $this->articleAssetsDir,
                $fileName
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        # Mise à jour de l'image
        $request->setFeaturedImage($fileName);

        # Mise à jour du slug
        $request->setSlug($this->slugify($request->getTitre()));

        # Appel de la Factory
        if (null==$request->getId()){
            $article = $this->articleFactory->createFromArticleRequest($request);
        } else {
            $article = $this->em->getRepository(Article::class)->find($request->getId());
            $article->setTitre($request->getTitre());
            $article->setSlug($request->getSlug());
            $article->setContenu($request->getContenu());
            $article->setCategorie($request->getCategorie());
            $article->setFeaturedImage($request->getFeaturedImage());
            $article->setSpecial($request->getSpecial());
            $article->setSpotlight($request->getSpotlight());
        }

        # Sauvegarde Doctrine
        $this->em->persist($article);
        $this->em->flush();

        return $article;

    }

    public function transform(Article $article): ArticleRequest
    {
        $articleRequest = new ArticleRequest($article->getMembre());
        $articleRequest->setId($article->getId());
        $articleRequest->setTitre($article->getTitre());
        $articleRequest->setSlug($article->getSlug());
        $articleRequest->setContenu($article->getContenu());
        $articleRequest->setFeaturedImage(new File($this->articleAssetsDir.'/'.$article->getFeaturedImage()));
        $articleRequest->setSpecial($article->getSpecial());
        $articleRequest->setSpotlight($article->getSpotlight());
        $articleRequest->setCategorie($article->getCategorie());
        $articleRequest->setDateCreation($article->getDateCreation());
        $articleRequest->setImageUrl($this->articleAssetsDir.'/'.$article->getFeaturedImage());
        return $articleRequest;
    }
}