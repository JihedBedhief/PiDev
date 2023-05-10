<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Vente;
use App\Entity\PointVente;

// use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class VenteType extends AbstractType
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
            ->add('produit')
            ->add('quantite')
            ->add('prix_unite')
            ->add('taxe')
            ->add('date_vente', DateType::class, [
                'years' => range(date('Y'), date('Y')-67)])
            ->add('client')
            ->add('PointVente')
            ->add('User', EntityType::class, [
                'class' => User::class,
                'required' => true,
                'data' => $currentUser,
                'choice_label' => 'id', // or any other property of User entity you want to display as the label
                'attr' => [
                    'class' => 'form-control'
                ],
            ])
            // ->add('captcha', CaptchaType::class, [
            //     'width' => 200,
            //     'height' => 50,
            //     'length' => 6,
            //     'invalid_message' => 'Captcha is not valid',
            //     'attr' => [
            //         'class' => 'captcha-field',
            //         'placeholder' => 'Captcha',
            //     ],
            // ])
            ->add('ajouter',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vente::class,
        ]);
    }
}
