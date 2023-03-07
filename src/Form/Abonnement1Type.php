<?php

namespace App\Form;

use App\Entity\Abonnement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class Abonnement1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'vip' => 'vip',
                    'classic' => 'classic',
                ],] )
            ->add('duree', ChoiceType::class, [
                'choices' => [
                    '3mois' => '3mois',
                    '6mois' => '6mois',
                    '12mois' => '12mois',

                ],] )
            ->add('frais', ChoiceType::class, [
                'choices' => [
                    '100DT' => '100DT', ],] )
            
            ->add('ajouter',SubmitType::class)

            >add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'register',
                             
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Abonnement::class,
        ]);
    }
}