<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => array(
                    'placeholder' => 'enter title...'
                ), 'label' => false
                ])
            ->add('releaseYear', IntegerType::class, [
                'attr' => array(
                    'placeholder' => 'release year'
                ), 'label' => false
                ])
            ->add('description', TextType::class, [
                'attr' => array(
                    'placeholder' => 'description'
                ), 'label' => false
                ])
            ->add('imagePath', FileType::class, [
                'attr' => array(
                    'placeholder' => 'image'
                ), 'label' => false
                ])
            //->add('actors')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
