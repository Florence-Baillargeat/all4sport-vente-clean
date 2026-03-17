<?php

namespace App\Form;

use App\Entity\Sport;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ── Champs de Credential ──
            ->add('email', EmailType::class, [
                'label'    => 'Adresse email',
                'required' => false,
                'attr'     => ['placeholder' => 'Laissez vide pour ne pas changer'],
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type'            => PasswordType::class,
                'mapped'          => false,
                'required'        => false,
                'first_options'   => [
                    'label'    => 'Nouveau mot de passe',
                    'required' => false,
                    'attr'     => ['placeholder' => 'Laissez vide pour ne pas changer'],
                ],
                'second_options'  => [
                    'label'    => 'Confirmer le nouveau mot de passe',
                    'required' => false,
                ],
                'invalid_message' => 'Les mots de passe ne correspondent pas.',
                'constraints'     => [
                    new Length([
                        'min'        => 6,
                        'max'        => 4096,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ])

            // ── Champs de User (via property_path) ──
            ->add('nom', TextType::class, [
                'property_path' => 'user.nom',
                'required'      => false,
                'label'         => 'Nom',
            ])

            ->add('prenom', TextType::class, [
                'property_path' => 'user.prenom',
                'required'      => false,
                'label'         => 'Prénom',
            ])

            ->add('adresse', TextType::class, [
                'property_path' => 'user.adresse',
                'required'      => false,
                'label'         => 'Adresse',
            ])

            ->add('telephone', TelType::class, [
                'property_path' => 'user.telephone',
                'required'      => false,
                'label'         => 'Téléphone',
            ])

            ->add('dateNaissance', DateType::class, [
                'property_path' => 'user.dateNaissance',
                'widget'        => 'single_text',
                'html5'         => true,
                'required'      => false,
                'label'         => 'Date de naissance',
            ])

            ->add('sports', EntityType::class, [
                'property_path' => 'user.sport_id',         // si tu utilises property_path
                'class'         => Sport::class,
                'choice_label'  => 'libelle',             // ← ICI la correction
                'multiple'      => true,
                'expanded'      => true,                  // checkboxes
                'required'      => false,
                'label'         => 'Sports pratiqués',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'        => \App\Entity\Credential::class,
            'validation_groups' => ['Default', 'update'],
        ]);
    }
}