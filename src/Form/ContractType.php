<?php

namespace App\Form;

use App\Entity\Rh;
use App\Entity\Contract;
use Assert\LessThanOrEqual;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('employer')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'CIVP ' => 'CIVP',
                    'CDI ' => 'CDI',
                    'CDD ' => 'CDD',
                    'CDD Senior ' => 'CDD Senior',
                    'CDI intérimaire ' => 'CDI intérimaire',
                    'seasonal contract  ' => 'seasonal contract                    ',
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('duration')
           //->add('duration', IntegerType::class, [
            //    'label' => 'Age',
             //   'required' => true,
             //   'constraints' => [
            //        new LessThanOrEqual([
              //          'value' => 5,
             //           'message' => 'The duration must be less than or equal to {{ compared_value }}.',
             //       ]),
            //    ],
        //    ])
            ->add('salary')
            ->add('Create',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contract::class,
        ]);
    }
}
