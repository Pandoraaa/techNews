<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 13/11/18
 * Time: 11:03
 */

namespace App\Membre;


use App\Entity\Membre;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MembreFactory
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function createFromMembreRequest(MembreRequest $request): Membre
    {
        $membre = new Membre();
        $membre->setPrenom($request->getPrenom());
        $membre->setNom($request->getNom());
        $membre->setEmail($request->getEmail());
        $membre->setRoles($request->getRoles());
        $membre->setPassword($this->encoder->encodePassword($membre, $request->getPassword()));

        return $membre;
    }

    public function createFromYaml($data)
    {
        $membre = new Membre();
        $membre->setNom($data['auteur']['nom']);
        $membre->setPrenom($data['auteur']['prenom']);
        $membre->setEmail($data['auteur']['email']);
        $membre->setPassword($this->encoder->encodePassword($membre, $data['auteur']['email']));
        $membre->setRoles(['ROLE_AUTEUR']);

        return $membre;
    }
}