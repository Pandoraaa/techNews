<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 15/11/18
 * Time: 15:08
 */

namespace App\Membre;


use App\Entity\Membre;
use Symfony\Component\EventDispatcher\Event;

class MembreEvent extends Event
{
    private $membre;

    /**
     * MembreEvent constructor.
     * @param Membre $membre
     */
    public function __construct(Membre $membre)
    {
        $this->membre = $membre;
    }

    /**
     * @return Membre
     */
    public function getMembre(): Membre
    {
        return $this->membre;
    }


}