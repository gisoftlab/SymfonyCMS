<?php

namespace App\TestimonialBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TestimonialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', TextType::class, array('label' => 'Email', 'required' => true));
        $builder->add('name', TextType::class, array('label' => 'Imię', 'required' => true));
        $builder->add('city', TextType::class, array('label' => 'Miasto zamieszkania', 'required' => true));
        $builder->add('message', TextareaType::class, array('label' => 'Opinie', 'required' => true));
        $builder->add('published', CheckboxType::class, array(
            'label' => 'Publikowano'
        , 'required' => false
        , 'label_attr' => array('class'=>'checkbox-inline')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => \App\TestimonialBundle\Entity\Testimonial::class,
            )
        );
    }

    public function getName() {
        return 'testimonial';
    }
}