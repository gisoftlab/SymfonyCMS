<?php

namespace App\UserBundle\Form;

use App\UserBundle\Entity\Group;
use App\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class, array(
            'label' => 'Nazwa użytkownika'
        ));
        $builder->add('email', TextType::class, array(
            'label' => 'Email'
        ));
        $builder->add('firstname', TextType::class, array(
            'label' => 'Imię'
        ));
        $builder->add('lastname', TextType::class, array(
            'label' => 'Nazwisko'
        ));
        $builder->add('plainPassword', PasswordType::class, array(
            'label' => 'Hasło', 'required' => false
        ));
        $builder->add('enabled', CheckboxType::class, array(
            'label' => 'Włączone', 'required' => false
        ));

//        $builder->add('groups', 'entity', array(    
//            'class' => 'AppUserBundle:Group',
//            'query_builder' => function(EntityRepository $er) {
//                return $er->createQueryBuilder('u')
//                    ->orderBy('u.name', 'ASC');
//            },
//        ));

        $builder->add('groups', null, array(
                'label' => 'Uprawnienia',
                'required' => true,
                'multiple' => true,
                'expanded' => false,
                'query_builder' => function ($er) {
                    $qb = $er->createQueryBuilder('p');
                    $qb->orderBy('p.name', 'ASC');

                    return $qb;
                },
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(
            array(
                'data_class' => User::class,
                'required' => false
            )
        );
    }

    public function getName(){
         return 'user';
    }
}
