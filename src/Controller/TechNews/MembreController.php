<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 13/11/18
 * Time: 10:53
 */

namespace App\Controller\TechNews;


use App\Membre\MembreRequest;
use App\Membre\MembreRequestHandler;
use App\Membre\MembreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MembreController extends Controller
{
    /**
     * Inscription d'un utilisateur
     * @Route("/inscription", name="membre_inscription", methods={"GET", "POST"})
     * @param Request $request
     * @param MembreRequestHandler $membreRequestHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function inscription(Request $request, MembreRequestHandler $membreRequestHandler)
    {
        # Création d'un nouvel utilisateur
        $membre = new MembreRequest();

        # Création d'un formulaire
        $form = $this->createForm(MembreType::class, $membre)
            ->handleRequest($request);

        # Vérification et traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {

            # Enregistrement d'un nouvel utilisateur
            $membre = $membreRequestHandler->handle($membre);

            # Flash Message
            $this->addFlash('notice', 'Félicitations vous pouvez vous connecter!');

            #Redirection
            return $this->redirectToRoute('security_connexion');
        }

        # Affichage du formulaire
        return $this->render('membre/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }
}