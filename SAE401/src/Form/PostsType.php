<?php

namespace App\Form;

use App\Entity\Activities;
use App\Entity\Difficulties;
use App\Entity\Posts;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PostsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gpxFile', FileType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['application/gpx+xml', 'application/octet-stream', 'text/xml'],
                        'mimeTypesMessage' => 'Veuillez uploader un fichier GPX valide.',
                    ]),
                ],
            ])
            ->add('title', TextType::class, [
                'attr' => ['placeholder' => 'Nom du tracé'],
            ])
            ->add('city', TextType::class, [
                'attr' => ['placeholder' => 'Ville'],
            ])
            ->add('region', TextType::class, [
                'attr' => ['placeholder' => 'Région'],
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Description ...',
                    'rows' => 15,
                ],
            ])
            ->add('activity', EntityType::class, [
                'class' => Activities::class,
                'choice_label' => 'name',
            ])
            ->add('difficulty', EntityType::class, [
                'class' => Difficulties::class,
                'choice_label' => 'name',
            ])
            ->add('images', FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false,
            ]);

        if ($options['is_guide'] === true) {
            $builder->add('is_certified', CheckboxType::class, [
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
            'is_guide' => false, 
        ]);
    }
}
