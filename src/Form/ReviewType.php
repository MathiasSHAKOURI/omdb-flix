<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom'
                ])
            ->add('email', EmailType::class, [
                'label' => 'Courriel'
                ])
            ->add('content', TextareaType::class, [
                'label' => 'Critique'
                ])
            ->add('rating', ChoiceType::class, [
                'choices'  => [
                    'Excellent' => 5,
                    'Très bon' => 4,
                    'Bon' => 3,
                    'Peut mieux faire' => 2,
                    'A éviter' => 1,
                ],
                'placeholder' => 'Votre choix ...'
            ])
            ->add('reaction', ChoiceType::class, [
                'choices'  => [
                    "Rire" => 'Rire',
                    "Pleurer" => 'Pleurer',
                    "Réfléchir" => 'Réfléchir',
                    "Dormir" => 'Dormir',
                    "Rêver" => 'Rêver'
                ],
                 'multiple' => true,
                 'expanded' => true
            ])
            ->add('watchedAt', DateType::class, [
                'label' => 'Vous avez vu ce film le ...',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('movie', EntityType::class, [
                'class' => Movie::class,
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
