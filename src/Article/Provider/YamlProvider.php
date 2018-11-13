<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 07/11/18
 * Time: 15:40
 */

namespace App\Article\Provider;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;


class YamlProvider
{
    /**
     * Retourne les articles.yaml sous forme de tableau
     */
    public function getArticles()
    {
        try {
            $articles = Yaml::parseFile(__DIR__ . '/articles.yaml')['data'];
            return $articles;
        } catch (ParseException $exception) {
            printf('Unable to parse the YAML string: %s', $exception->getMessage());
        }
    }
}