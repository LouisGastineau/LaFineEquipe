<?php

namespace App\Form;

use App\Entity\Workshop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class WorkshopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'titre'
            ])
            ->add('description', TextType::class, [
                'label' => 'description de l’atelier'
            ])
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('capacity')
            ->add('categories', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'title',
                'multiple' => 'true'
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image de l’atelier',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpeg', 'image/png', 'image/webp'],
                        'mimeTypesMessage' => 'Merci de choisir une image valide (JPG, PNG, WEBP)',
                    ])
                ],
                
            ]) 
            ->add('deleteImage', CheckboxType::class, [
                'label' => 'Supprimer l’image actuelle',
                'mapped' => false,
                'required' => false,]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Workshop::class,
        ]);
    }
}
