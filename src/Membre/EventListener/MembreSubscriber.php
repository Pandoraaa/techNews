<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 15/11/18
 * Time: 14:08
 */

namespace App\Membre\EventListener;

use App\Entity\Membre;
use App\Entity\Newsletter;
use App\Membre\MembreEvent;
use App\Membre\MembreEvents;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class MembreSubscriber implements EventSubscriberInterface
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            SecurityEvents::INTERACTIVE_LOGIN => 'onInteractiveLogin',
            MembreEvents::MEMBRE_CREATED => 'onMembreCreated'
        ];
    }

    /**
     * Met à jour la date de dernière connexion du membre qui s'est connecté
     * @param InteractiveLoginEvent $event
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $membre = $event->getAuthenticationToken()->getUser();

        # Mettre à jour la date de dernière connexion
        if ($membre instanceof Membre) {

            # Mise à jour du timestamp
            $membre->setDerniereConnexion();

            # Sauvegarde en DB
            $this->em->flush();
        }
    }

    public function onMembreCreated(MembreEvent $event)
    {
        $newsletter = new Newsletter();
        $newsletter->setEmail($event->getMembre()->getEmail());
        $this->em->persist($newsletter);
        $this->em->flush();
    }

}