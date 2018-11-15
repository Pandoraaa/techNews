<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 15/11/18
 * Time: 10:26
 */

namespace App\Controller\TechNews;


use App\Newsletter\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NewsletterController extends Controller
{
    /**
     * Affichage d'une Modal Newsletter
     */
    public function newsletter()
    {
        # Récupération du formulaire
        $form = $this->createForm(NewsletterType::class);

        # Transmission à la vue
        return $this->render('newsletter/_modal.html.twig', [
            'form' => $form->createView()
        ]);
    }
}