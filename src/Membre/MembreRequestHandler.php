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

class MembreRequestHandler
{
    private $manager, $membreFactory;

    public function __construct(ObjectManager $manager, MembreFactory $membreFactory)
    {
        $this->manager = $manager;
        $this->membreFactory = $membreFactory;
    }

    public function handle(MembreRequest $request): Membre
    {
        # Création de l'objet Membre
        $membre = $this->membreFactory->createFromMembreRequest($request);

        # Sauvegarde dans la DB
        $this->manager->persist($membre);
        $this->manager->flush();

        # On retourne le nouvel utilisateur
        return $membre;
    }
}