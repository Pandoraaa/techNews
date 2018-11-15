<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 14/11/18
 * Time: 16:21
 */

namespace App\Article\EventListener;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ArticleTypeSlugFieldSubscriber implements EventSubscriberInterface
{

    /**
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData'
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        $article = $event->getData();
        $form = $event->getForm();

        # Je vérifie que j'ai bien un slug
        if (null !== $article->getSlug()) {

            # Je rajoute le champ correspondant
            $form->add('slug', TextType::class, [
                'label' => 'Alias',
                'attr' => [
                    'placeholder' => "Alias de l'article"
                ]
            ]);
        }
    }
}