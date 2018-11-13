<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 07/11/18
 * Time: 10:52
 */

namespace App\Controller\TechNews;


use App\Entity\Article;
use App\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Article\Provider\YamlProvider;

class IndexController extends Controller
{
    /**
     * Page d'accueil de notre site internet.
     * @param YamlProvider $yamlProvider
     * @return Response
     */
    public function index(YamlProvider $yamlProvider) {
        # Récupération des Articles depuis YamlProvider
        #$articles = $yamlProvider->getArticles();

        # Récupération des Articles de la DB
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        # Récupération des Articles Spotlight
        $spotlights = $this->getDoctrine()->getRepository(Article::class)->findSpotlightArticles();

        return $this->render('index/index.html.twig', [
            'articles' => $articles,
            'spotlights' => $spotlights
        ]);

        #return new Response("<html><body><h1>PAGE D'ACCUEIL</h1></body></html>");
        #return $this->render('index/index.html.twig');

    }

    /**
     * Page permettant d'afficher les articles d'une catégorie.
     * @Route("/categorie/{slug<\w+>}",
     *     name="index_categorie",
     *     defaults={"slug":"breaking-news"},
     *     requirements={"slug"="\w+"},
     *     methods={"GET"})
     * @param Categorie $categorie
     * @param $slug
     * @return Response
     */
    public function categorie(Categorie $categorie, $slug)
    {
        #return new Response("<html><body><h1>PAGE CATEGORIE : $categorie</h1></body></html>");

        # Récupération de la categorie
//        $category = $this->getDoctrine()->getRepository(Categorie::class)
//            ->findOneBy(['slug' => $categorie]);

        # Récupérer les articles de la catégorie
//        $articles = $category->getArticles();

//        $categorieArticles = $this->getDoctrine()
//            ->getRepository(Categorie::class)
//            ->findOneBySlug($slug)
//            ->getArticles();

        $articles = $categorie->getArticles();
        if (null === $categorie) {
            # On redirige
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);
        }

//        return $this->render('index/categorie.html.twig', [
//            'categorieArticles' => $categorieArticles
//        ]);

        return $this->render('index/categorie.html.twig', [
            'categorie' => $categorie,
            'articles' => $articles
        ]);
    }

    /**
     * Afficher un Article
     * @Route("/{categorie<\w+>}/{slug}_{id<\d+>}.html",
     *     name="index_article")
     * @param Article $article
     * @param $categorie
     * @return Response
     */
    public function article(Article $article = null)
    {
//        $article = $this->getDoctrine()
//            ->getRepository(Article::class)
//            ->find($id);
        if (null === $article) {
            # On redirige
            return $this->redirectToRoute('index', [], Response::HTTP_MOVED_PERMANENTLY);

            # On lance une exception
//            throw $this->createNotFoundException(
//                'Nous n\'avons pas trouvé votre article id: ' . $id
//            );

            # On pourrait vérifier également, l'intégralité de l'url.
        }

        # Récupération des suggestions
        $suggestions = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findArticlesSuggestions($article->getId(), $article->getCategorie()->getId());

        # Transmission des données à la vue
        return $this->render('index/article.html.twig', [
            'article' => $article,
            'suggestions' => $suggestions
        ]);
    }

    public function sidebar()
    {
        # Récupération du Repository
        $repository = $this->getDoctrine()
            ->getRepository(Article::class);

        # Récupération des 5 derniers articles
        $articles = $repository->findLastestArticles();

        # Récupération des articles à la position "special"
        $specials = $repository->findSpecialArticles();

        # Rendu de la vue
        return $this->render('components/_sidebar.html.twig', [
            'articles' => $articles,
            'specials' => $specials
        ]);
    }

}