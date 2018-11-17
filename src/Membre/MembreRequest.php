<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 13/11/18
 * Time: 10:16
 */

namespace App\Membre;

use Symfony\Component\Validator\Constraints as Assert;

class MembreRequest
{
    /**
     * @Assert\NotBlank(message="asserts.membre.notblank.firstname")
     * @Assert\Length(max="50", maxMessage="asserts.membre.length.max.firstname")
     */
    private $prenom;

    /**
     * @Assert\NotBlank(message="asserts.membre.notblank.name")
     * @Assert\Length(max="50", maxMessage="asserts.membre.length.max.name")
     */
    private $nom;

    /**
     * @Assert\Email(message="asserts.membre.email")
     * @Assert\NotBlank(message="asserts.membre.notblank.email")
     * @Assert\Length(max="80", maxMessage="asserts.membre.length.max.email")
     */
    private $email;

    /**
     * @Assert\NotBlank(message="asserts.membre.notblank.password")
     * @Assert\Regex(pattern="/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])[a-zA-Z0-9]+$/",
     *     message="asserts.membre.regex.password")
     * @Assert\Length(
     *      min="8", minMessage="asserts.membre.length.min.password",
     *      max="20", maxMessage="asserts.membre.length.max.password")
     */
    private $password;

    /**
     * @Assert\IsTrue(message="asserts.membre.istrue.cgu")
     */
    private $conditions;

    private $dateInscription;

    private $roles = [];

    /**
     * MembreRequest constructor.
     * @param $role
     */
    public function __construct(string $role = 'ROLE_MEMBRE')
    {
        $this->dateInscription = new \DateTime();
        $this->addRole($role);
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param mixed $conditions
     */
    public function setConditions($conditions): void
    {
        $this->conditions = $conditions;
    }

    /**
     * @return array|null
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function addRole(string $role): bool
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
            return true;
        } else {
            return false;
        }
    }

}