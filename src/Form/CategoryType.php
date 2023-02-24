<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description',ChoiceType::class , [
                'placeholder' => 'Choose an option',
                'required' => true,

            'choices'=> [
            'Électronique' => 'Électronique',
            'Vêtements et accessoires' =>'Vêtements et accessoires',
            'Aliments et boissons' =>'Aliments et boissons',
            'Sports et loisirs' =>'Sports et loisirs',
            'Beauté et santé' =>'Beauté et santé',
            'Jouets et jeux' =>'Jouets et jeux',

            
            ],])
            ->add('ADD',SubmitType::class)
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
