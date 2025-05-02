<?php

namespace App\Form;

use App\Entity\Organisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class OrganisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(min: 2, max: 255),
//                    new Assert\Unique(message: 'An organization with this name already exists.'),
                ]
            ])
            ->add('address', TextType::class, [
                'required' => true,
            ])
            ->add('addressCoordonates', TextType::class, [
                'required' => false,
            ])
            ->add('representative', TextType::class, [
                'required' => false,
            ])
            ->add('avatarFile', FileType::class, [
                'required' => false,
                'label' => 'Organisation avatar',
                'constraints' => [
                    new Assert\file([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Sorry, your picture is not valid.',
                    ])
                ]
            ])
            ->add('activityPictureFile', FileType::class, [
                'required' => false,
                'label' => 'Activity picture',
                'constraints' => [
                    new Assert\file([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                            'image/webp'
                        ],
                        'mimeTypesMessage' => 'Sorry, your picture is not valid.',
                    ])
                ]
            ])
            ->add('presentation', TextareaType::class, [
                'required' => true,
            ])
            ->add('keywords', CollectionType::class, [
                'required' => true,
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('links', CollectionType::class, [
                'required' => false,
                'entry_type' => TextType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Organisation::class,
        ]);
    }
}
