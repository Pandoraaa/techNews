<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 08/11/18
 * Time: 11:57
 */

namespace App\Controller\TechNews;


use App\Article\ArticleRequest;
use App\Article\ArticleRequestHandler;
use App\Article\ArticleType;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Membre;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\HelperTrait;

class ArticleController extends Controller
{
    use HelperTrait;

    /**
     * Démonstration de l'ajout d'un article avec Doctrine.
     * @Route("test/article/add", name="article_test")
     */
    public function test()
    {
        # Création d'une catégorie
        $categorie = new Categorie();
        $categorie->setNom('Economie');
        $categorie->setSlug('economie');

        # Création du Membre (Auteur de l'article)
        $membre = new Membre();
        $membre->setPrenom('Fabien');
        $membre->setNom('BRIVE');
        $membre->setEmail('f.brive@tech.news');
        $membre->setPassword('monchatfaitdescalins');
        $membre->setRoles(['ROLE_AUTEUR']);

        # Création de l'article
        $article = new Article();
        $article->setTitre('WF3 rachète un vidéo-projecteur');
        $article->setSlug('wf3-rachete-un-vidéo-projecteur');
        $article->setContenu("<p> Il était enfin temps d'acheter un nouveau vidéo-projecteur pour la formation de Nicolas</p>");
        $article->setFeaturedImage('7.jpg');
        $article->setSpecial(0);
        $article->setSpotlight(1);

        # On associe une catégorie et un auteur à l'article
        $article->setCategorie($categorie);
        $article->setMembre($membre);

        # On sauvegarde le tout avec Doctrine
        $em = $this->getDoctrine()->getManager();
        $em->persist($categorie);
        $em->persist($membre);
        $em->persist($article);
        $em->flush();

        return new Response(
            'Nouvel article id: '
            . $article->getId()
            . ' dans la catégorie : '
            . $categorie->getNom()
            . ' de l\'auteur '
            . $membre->getPrenom()
        );

    }

    /**
     * Formulaire pour ajouter un Article
     * @Route("/creer-un-article",
     *     name="article_new")
     * @Security("has_role('ROLE_AUTEUR')")
     * @param Request $request
     * @param ArticleRequestHandler $articleRequestHandler
     * @return Response
     */
    public function newArticle(Request $request, ArticleRequestHandler $articleRequestHandler)
    {
        # Récupération de l'auteur ou en session
//        $membre = $this->getDoctrine()
//            ->getRepository(Membre::class)
//            ->find(1);

        # Création d'un Article
//        $article = new Article();
//        $article->setMembre($membre);

        $article = new ArticleRequest($this->getUser());

        # Créer un Formulaire permettant l'ajout d'un Article
        $form = $this->createForm(ArticleType::class, $article)
            ->handleRequest($request);

        # Traitement des données POST
//        $form->handleRequest($request);

        # Vérification des données du Formulaire
        if ($form->isSubmitted() && $form->isValid()) {

//            # Récupération des données
//            /** @var Article $article */
//            $article = $form->getData();

            # Une fois le formulaire soumis et valide,
            # on passe nos données directement au service qui se chargera du traitement de l'article.
            $article = $articleRequestHandler->handle($article);

            if (null !== $article) {
                # Flash Message
                $this->addFlash('notice',
                    'Félicitation, votre article est en ligne!');

                # Redirection vers l'Article créé
                return $this->redirectToRoute('index_article', [
                    'categorie' => $article->getCategorie()->getSlug(),
                    'slug' => $article->getSlug(),
                    'id' => $article->getId()
                ]);
            } else {
                # Flash Message
                $this->addFlash('error', 'Une erreur est survenue. Vérifiez vos informations.');
            }

        }

        # Affichage du Formulaire dans la vue
        return $this->render('article/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{categorie<\w+>}/edit/{slug}_{id<\d+>}.html",
     *     name="edit_article")
     * @param Request $request
     * @param ArticleRequestHandler $articleRequestHandler
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function editArticle(Request $request, ArticleRequestHandler $articleRequestHandler, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);

        //Transforme Article en ArticleRequest
        $articleRequest = $articleRequestHandler->transform($article);
dump($articleRequest);
        $form = $this->createForm(ArticleType::class, $articleRequest)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $articleRequestHandler->handle($articleRequest);

            return $this->redirectToRoute('index_article', [
                'categorie' => $article->getCategorie()->getSlug(),
                'slug' => $article->getSlug(),
                'id' => $article->getId()
            ]);
        }

        return $this->render('article/form.html.twig', [
            'form' => $form->createView()
        ]);
    }



}