<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Purchase;
use App\Entity\Supplier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PurchaseType extends AbstractType
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
            ->add('product')
            ->add('qte')
            ->add('unitPrice')
            ->add('puchaseDate')
            ->add('taxeRate')
            ->add('User', EntityType::class, [
                'class' => User::class,
                'required' => true,
                'data' => $currentUser,
                'choice_label' => 'id', // or any other property of User entity you want to display as the label
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            ->add('supplier', EntityType::class, [
                'class' => Supplier::class,
                'choice_label' => 'name',
            ])
            

            ->add('add',SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
