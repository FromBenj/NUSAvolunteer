<?php

namespace App\Form;

use App\Entity\Volunteer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchVolunteersType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required'   => false,
                'label'      => 'Name',
            ])
            ->add('description', TextType::class, [
                'required'   => false,
                'label'      => 'description keywords',
            ])
            ->add('disponibilities', ChoiceType::class, [
                'choices'    => array_flip(Volunteer::DISPONIBILITYCHOICES),
                'required'   => false,
                'multiple'   => true,
                'expanded'   => true,
                'label'      => 'Disponibilities',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
