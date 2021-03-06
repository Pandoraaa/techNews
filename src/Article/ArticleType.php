<?php
/**
 * Created by PhpStorm.
 * User: pandora
 * Date: 12/11/18
 * Time: 14:04
 */

namespace App\Article;

use App\Article\EventListener\ArticleTypeSlugFieldSubscriber;
use App\Entity\Categorie;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            # Champ TITRE
            ->add('titre', TextType::class, [
                'required' => true,
                'label' => 'form.article.title',
                'attr' => [
                    'placeholder' => 'form.article.title.placeholder'
                ]
            ])
            # Champ CATEGORIE
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'label' => 'form.article.category',
                'choice_label' => 'nom',
                'expanded' => false,
                'multiple' => false
            ])
            # Champ CONTENU
            ->add('contenu', CKEditorType::class, [
                'required' => true,
                'label' => false,
                'config' => [
                    'toolbar' => 'standard'
                ]
            ])
            # Champ FEATUREDIMAGE
            ->add('featuredImage', FileType::class, [
                'required' => true,
                'label' => false,
                'attr' => [
                    'class' => 'dropify',
                    'data-default-file' => $options['image_url']
                ]
            ])
            # Champs SPECIAL & SPOTLIGHT
            ->add('special', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-on' => 'yes',
                    'data-off' => 'no'
                ]
            ])
            ->add('spotlight', CheckboxType::class, [
                'required' => false,
                'attr' => [
                    'data-toggle' => 'toggle',
                    'data-on' => 'yes',
                    'data-off' => 'no'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.article.publish'
            ])
            ->addEventSubscriber(new ArticleTypeSlugFieldSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Article::class
            'data_class' => ArticleRequest::class,
            'image_url' => null,
            'translation_domain' => 'forms'
        ]);
    }


}