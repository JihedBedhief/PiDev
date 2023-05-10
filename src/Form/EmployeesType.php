<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Employees;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EmployeesType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $this->security->getUser();
        $builder
           
        ->add('cin')
        ->add('nom')
        ->add('prenom')
        ->add('email')
        ->add('phoneNum')
        ->add('idComp', EntityType::class, [
            'class' => User::class,
            'required' => true,
            'data' => $currentUser,
            'choice_label' => 'id', // or any other property of User entity you want to display as the label
            'attr' => [
                'class' => 'form-control'
            ],
        ])
        ->add('Create',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employees::class,
        ]);
    }
}
