<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Volunteer;
use Doctrine\DBAL\Types\ArrayType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class VolunteerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new assert\NotBlank(),
                    new Assert\Length(min: 2, max: 255),
                ]
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new assert\NotBlank(),
                    new Assert\Length(min: 2, max: 255),
                ]
            ])
            ->add('address', TextType::class, [
                'required' => false,
            ])
            ->add('pictureName', TextType::class, [
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
            ])
            ->add('disponibilities', ArrayType::class, [
                'required' => false,
            ])
            ->add('keywords')
            ->add('slug')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Volunteer::class,
        ]);
    }
}
