<?php

namespace App\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ProductItemsType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('field',TextType::class, array('label'=>'Pole', 'required' => false));
        $builder->add('content',TextType::class, array('label'=>'Wartość', 'required' => false));
        $builder->add('description', TextareaType::class, array(
            'label'=>'Opis - niewyświetlany',
           'required' => false,
           'attr' => array('class' => 'tinymce', 'tinymce'=>'{"theme":"simple"}'))
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => \App\ProductBundle\Entity\ProductItems::class,
            )
        );
    }

    public function getName()
    {
        return 'productItems';
    }
}
