<?php

namespace App\SettingsBundle\Form;

use App\SettingsBundle\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SettingsType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('label' => 'Nazwa'));
        $builder->add('value', TextType::class, array('label' => 'Wartość'));
        $builder->add(
            'description',
            TextareaType::class,
            array(
                'label' => 'Opis',
                'required' => false,
                'attr' => array('class' => 'tinymce', 'tinymce' => '{"theme":"simple"}'),
            )
        );
        //$builder ->add('translations', 'a2lix_translations');

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Settings::class,
                'required' => true
            )
        );
    }

    public function getName()
    {
        return 'settings';
    }
}
