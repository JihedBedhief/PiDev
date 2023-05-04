<?php

namespace App\Form;

use App\Entity\PointVente;
use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PointVenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('region')
            ->add('ville')
            ->add('code_postal')
            ->add('telephone')
            ->add('email')
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
            'data_class' => PointVente::class,
        ]);
    }
}
