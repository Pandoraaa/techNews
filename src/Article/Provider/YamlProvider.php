<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 07/11/18
 * Time: 15:40
 */

namespace App\Article\Provider;

use Symfony\Component\HttpKernel\KernelInterface;


class YamlProvider
{
    private $kernel;

    /**
     * YamlProvider constructor.
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * Retourne les articles.yaml sous forme de tableau
     */
    public function getArticles()
    {
        $articles = unserialize(file_get_contents(
            $this->kernel->getCacheDir().'/yaml-articles.php'
        ));

        return $articles;
    }
}