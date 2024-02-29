<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserType extends AbstractType
{
    private $authorizationChecker;


    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    "Manager" => 'ROLE_MANAGER',
                    "Administrateur" => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true
            ]);

        if ($options["custom_option"] !== "edit") {
            $builder
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options'  => ['label' => 'Password', 'help' => 'Le mot de passe doit avoir minimum 4 caractères'],
                    'second_options' => ['label' => 'Repeat Password'],
                    'invalid_message' => 'Les mots de passe saisis ne sont pas identiques',
                ]);
        };
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            "custom_option" => "default"
        ]);
    }
}
