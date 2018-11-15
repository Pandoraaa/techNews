<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 15/11/18
 * Time: 10:47
 */

namespace App\Newsletter;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class NewsletterSubscriber implements EventSubscriberInterface
{
    private $session;

    /**
     * NewsletterSubscriber constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse'
        ];
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        # faire en sorte que la modale ne s'affiche qu'à la 3eme page et utiliser session php

        # On s'assure que la requête vient de l'utilisateur et non de Symfony
        if (!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        # Incrémentation du nombre de pages visitées par mon utilisateur
        $this->session->set('countVisitedPages',
            $this->session->get('countVisitedPages', 0) + 1);

        # Inviter l'utilisateur
        if ($this->session->get('countVisitedPages') === 3) {
            $this->session->set('inviteUserModal', true);
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        # On s'assure que la requête vient de l'utilisateur et non de Symfony
        if (!$event->isMasterRequest() || $event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        # On passe à false l'inviteUserModal
//        if ($this->session->get('countVisitedPages') >= 3) {
//            $this->session->set('inviteUserModal', false);
//        }
        $this->session->set('inviteUserModal', false);
    }
}