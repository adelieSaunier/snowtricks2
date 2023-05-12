<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Tricks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Repository\CategoriesRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Image;

class TricksFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $trick = $options['data'] ?? null;
        $isEdit = $trick && $trick->getId();

        $builder

            ->add('mainimage', FileType::class,[
                'label' => "Image à la une",
                'multiple' => false,
                'mapped' => false,
                'required' => !$isEdit
            ])

            ->add('name', options:[
                'label'=> 'Nom'
            ])

            ->add('description', options:[
                'label'=> 'Description'
            ] )

            ->add('categories', EntityType::class,  options:[
                'class'=> Categories::class,
                'choice_label' => 'name', 
                'label' => 'Catégories',
                'query_builder' => function(CategoriesRepository $cr)
                {
                    return $cr->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
                }
            ])

            ->add('images', FileType::class, [
                'label' => false,
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                /*'constraints' => [
                    new All(
                        new Image([
                            'message' => 'Les fichiers doivent être des images'
                        ])
                    )
                ]*/
            ])

            ->add('videos', CollectionType::class, [
                'entry_type' => VideosFormType::class,
                'label' => 'Vidéos',
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false // va chercher la fonction addVideo
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
