<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 15/11/18
 * Time: 15:03
 */

namespace App\Membre;

/**
 * Définir nos évènements
 * Class MembreEvents
 * @package App\Membre
 */
final class MembreEvents
{
    public const MEMBRE_CREATED = 'membre.created';
    public const MEMBRE_UPDATED = 'membre.updated';
    public const MEMBRE_DELETED = 'membre.deleted';
}