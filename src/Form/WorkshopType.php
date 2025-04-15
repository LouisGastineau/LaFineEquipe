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
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


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
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',  // Affiche un champ de texte unique
                'label' => 'Date et Heure',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez remplir ce champ.',
                    ]),
                    new Callback([
                        'callback' => [$this, 'validateDateTime'],  // Callback pour valider la date et l'heure combinées
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'YYYY-MM-DD HH:MM',  // Aide au format attendu
                ],
            ])
            ->add('capacity', NumberType::class, [
                'label' => 'Nombre de participants maximum',
                'attr' => [
                    'min' => 1, // Minimum de participants
                    'step' => 1, // que des nombres entiers
                ],
                'required' => true,
            ])
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

    public function validateDateTime($value, ExecutionContextInterface $context)
{
    // Vérifier si la valeur est bien une date et une heure valides
    if (!$value instanceof \DateTime) {
        $context->buildViolation('Veuillez entrer une date et une heure valides.')
                ->atPath('date') 
                ->addViolation();
    }
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Workshop::class,
        ]);
    }
}
