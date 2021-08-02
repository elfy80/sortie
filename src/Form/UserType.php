<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Config\Monolog\HandlerConfig\EmailPrototypeConfig;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // ->add('email',EmailPrototypeConfig::class, [
            //    'label' => 'email'])
            ->add('roles')
            ->add('password')
            ->add('firstname', TextType::class, [
                'label' => 'Nom'])
            ->add('lastname', TextType::class, [
                'label' => 'Prenom'])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo'])
            ->add('telephone')
            ->add('password')
            ->add('confirmation')
            ->add('campus')
            ->add('photo')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
