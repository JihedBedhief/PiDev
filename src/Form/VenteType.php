<?php

namespace App\Form;

use App\Entity\Vente;
use App\Entity\PointVente;
use Symfony\Component\Form\AbstractType;

use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class VenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('produit')
            ->add('quantite')
            ->add('prix_unite')
            ->add('taxe')
            ->add('date_vente', DateType::class, [
                'years' => range(date('Y'), date('Y')-67)])
            ->add('client')
            ->add('PointVente')
            ->add('captcha', CaptchaType::class, [
                'width' => 200,
                'height' => 50,
                'length' => 6,
                'invalid_message' => 'Captcha is not valid',
                'attr' => [
                    'class' => 'captcha-field',
                    'placeholder' => 'Captcha',
                ],
            ])
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
