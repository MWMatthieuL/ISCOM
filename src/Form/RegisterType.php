<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $type = $options['type'];

        switch ($type) {
            case 'student':
                $emailPlaceholder = 'Email';
                $builder
                    ->add('lastName', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Nom étudiant'
                        ],
                        'required' => true
                    ])
                    ->add('firstName', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Prénom étudiant'
                        ],
                        'required' => true
                    ])
                    ->add('phone', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Numéro de téléphone'
                        ],
                        'required' => true,
                    ])
                    ->add('imageFile', VichImageType::class, [
                        'label' => false,
                        'translation_domain' => 'messages',
                        'required' => false,
                        'allow_delete' => true,
                        'delete_label' => 'user.delete',
                        'download_label' => 'user.download',
                        'download_uri' => true,
                        'image_uri' => false,
                    ])
                    ->add('conditions', CheckboxType::class, [
                        'label' => "J'accepte les <a href='/cgu'> conditions générales d'utilisation </a>",
                        'label_html' => true,
                        'mapped' => false,
                        'required' => true,
                    ])
                ;
                break;
            case 'company':
                $emailPlaceholder = 'Email contact';
                $builder
                    ->add('companyName', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Nom entreprise'
                        ],
                        'required' => true
                    ])
                    ->add('lastName', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Nom contact'
                        ],
                        'required' => true
                    ])
                    ->add('firstName', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Prénom contact'
                        ],
                        'required' => true
                    ])
                    ->add('phone', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Numéro de téléphone'
                        ],
                        'required' => true,
                    ])
                    ->add('imageFile', VichImageType::class, [
                        'label' => false,
                        'translation_domain' => 'messages',
                        'required' => false,
                        'allow_delete' => true,
                        'delete_label' => 'user.delete',
                        'download_label' => 'user.download',
                        'download_uri' => true,
                        'image_uri' => false,
                    ])
                    ->add('conditions', CheckboxType::class, [
                        'label' => "J'accepte les conditions générales d'utilisation",
                        'mapped' => false,
                        'required' => true,
                    ])
                ;
                break;
            case 'admin':
                $emailPlaceholder = 'Email administration';
                $builder
                    ->add('lastName', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Nom'
                        ],
                        'required' => true
                    ])
                    ->add('firstName', TextType::class, [
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'Prénom'
                        ],
                        'required' => true
                    ])
                ;
                break;
        }

        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => $emailPlaceholder,
                ],
                'required' => true
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les deux mots de passe doivent être identiques',
                'required' => true,
                'first_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Confirmation mot de passe'
                    ]
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'type' => null,
        ]);
    }
}
