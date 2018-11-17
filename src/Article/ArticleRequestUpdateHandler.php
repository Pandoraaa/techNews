<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 14/11/18
 * Time: 14:44
 */

namespace App\Article;


use App\Controller\HelperTrait;
use App\Entity\Article;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleRequestUpdateHandler
{
    use HelperTrait;

    private $em, $articleAssetsDir;

    public function __construct(ObjectManager $manager,
                                string $articleAssetsDir)
    {
        $this->em = $manager;
        $this->articleAssetsDir = $articleAssetsDir;
    }

    public function handle(ArticleRequest $request, Article $article): Article
    {
        # Traitement de l'upload de l'image  images/product
        /** @var UploadedFile $image */
        $image = $request->getFeaturedImage();

        /** TODO: virer l'ancienne image du serveur */
        # On vérifie
        if (null !== $image) {
            $fileName = $this->slugify($request->getTitre()) . '.' . $image->guessExtension();

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
        } else {
            $request->setFeaturedImage($article->getFeaturedImage());
        }

        if (null === $request->getSlug()) {
            $request->setSlug($this->slugify($request->getTitre()));
        }

        $article->update(
            $request->getTitre(),
            $this->slugify($request->getTitre()),
            $request->getContenu(),
            $request->getFeaturedImage(),
            $request->getSpecial(),
            $request->getSpotlight(),
            $request->getCategorie()
        );

        $this->em->flush();

        return $article;
    }
}