<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 13/11/18
 * Time: 14:14
 */

namespace App\Controller\TechNews\Security;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends Controller
{
    /**
     * Connexion d'un Membre
     * @Route("/connexion", name="security_connexion")
     */
    public function connexion()
    {

    }

    /**
     * Déconnexion d'un Membre
     * @Route("/deconnexion", name="security_deconnexion")
     */
    public function deconnexion()
    {

    }

    /**
     * Vous pourriez définir ici votre logique, mot de passe oublié...
     * Réinitialisation du mot de passe et email de validation.
     */
}