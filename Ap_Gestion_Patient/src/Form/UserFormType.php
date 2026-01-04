<?php

namespace App\Form;

use App\Entity\Localite;
use App\Entity\Service;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'];

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un email',
                    ]),
                    new Email([
                        'message' => 'Email invalide',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'exemple@email.com',
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administratif' => 'ROLE_ADMINISTRATIF',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Infirmier' => 'ROLE_INFIRMIER',
                ],
                'multiple' => true,
                'expanded' => true,
                'attr' => [
                    'class' => 'roles-checkbox',
                ],
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'libelle',
                'label' => 'Service',
                'placeholder' => 'Sélectionnez un service',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('localite', EntityType::class, [
                'class' => Localite::class,
                'choice_label' => function(Localite $localite) {
                    return $localite->getCodePostal() . ' - ' . $localite->getVille();
                },
                'label' => 'Localité',
                'placeholder' => 'Sélectionnez une localité',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);

        if (!$isEdit) {
            $builder->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Mot de passe',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'Confirmer le mot de passe',
                    ],
                ],
                'mapped' => true,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
        ]);
    }
}