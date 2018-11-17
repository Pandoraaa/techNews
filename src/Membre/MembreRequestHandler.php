<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 13/11/18
 * Time: 11:01
 */

namespace App\Membre;


use App\Entity\Membre;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MembreRequestHandler
{
    private $manager, $membreFactory, $dispatcher;

    public function __construct(ObjectManager $manager,
                                EventDispatcherInterface $dispatcher,
                                MembreFactory $membreFactory)
    {
        $this->manager = $manager;
        $this->membreFactory = $membreFactory;
        $this->dispatcher = $dispatcher;
    }

    public function handle(MembreRequest $request): Membre
    {
        # Création de l'objet Membre
        $membre = $this->membreFactory->createFromMembreRequest($request);

        # Sauvegarde dans la DB
        $this->manager->persist($membre);
        $this->manager->flush();

        # On émet notre évènement
        $this->dispatcher->dispatch(MembreEvents::MEMBRE_CREATED, new MembreEvent($membre));

        # On retourne le nouvel utilisateur
        return $membre;
    }
}