<?php

namespace App\Form;

use App\Entity\Rh;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RhType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('name')
            ->add('id_company')
            ->add('email')
            ->add('phone_number')
            ->add('fonction')
            ->add('departement')
           // ->add('contract')
           ->add('Create',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rh::class,
        ]);
    }
}
