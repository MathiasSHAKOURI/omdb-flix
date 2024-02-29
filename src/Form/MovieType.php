<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Par ex. Harry Potter'
                ]
            ])
            ->add('releaseDate', DateType::class, [
                'label' => 'Date de sortie',
                'widget' => 'choice',
                'years' => range(date('Y') - 100, date('Y') + 10),
                'data' => new \DateTimeImmutable('2000-01-01'),
                'input' => 'datetime_immutable'])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée en minutes'
            ])
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'Film' => 'Film',
                    'Série' => 'Série'
                ],
                'label' => 'Est-ce un film ou une série'
            ])
            ->add('summary', TextareaType::class, [
                'label' => 'Résumé',
                'attr' => [
                    'rows' => 3,
                ],
                'help' => '200 caractères max.'
            ])
            ->add('synopsis', TextareaType::class, [
                'label' => 'Synopsis',
                'attr' => [
                    'rows' => 6,
                ],
                'help' => '5000 caractères max.'
            ])
            ->add('poster', UrlType::class, [
                'label' => 'Affiche',
                'help' => 'Une URL en http:// ou https://',
                'default_protocol' => 'https'
            ])
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('g')
                    ->orderBy('g.name');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
