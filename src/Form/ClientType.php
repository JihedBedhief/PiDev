<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Client;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClientType extends AbstractType
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
            ->add('name')
            ->add('telephone')
            ->add('email')
            ->add('ville')
            ->add('code_postal')
            ->add('cin')
            ->add('division')
            ->add('User', EntityType::class, [
                'class' => User::class,
                'required' => true,
                'data' => $currentUser,
                'choice_label' => 'id', // or any other property of User entity you want to display as the label
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('DateAjout', DateType::class, [
                'years' => range(date('Y'), date('Y') - 67)])
            ->add('status', null , [
                'required'=> false ,
                'empty_data' => 'actif',])
            ->add('ajouter', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Client::class,
        ]);
    }
}
