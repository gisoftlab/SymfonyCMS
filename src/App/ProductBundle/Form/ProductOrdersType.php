<?php

namespace App\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ProductOrdersType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('name',TextType::class, array('label'=>'Imieę Nazwisko'));
        $builder->add('email',TextType::class, array('label'=>'email'));
        $builder->add('dateFrom',null, array('label'=>'Od'));     
        $builder->add('dateTo',null, array('label'=>'Do'));     
        $builder->add('description', TextareaType::class, array(
            'label'=>'Opis ',
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
                'data_class' => \App\ProductBundle\Entity\ProductOrders::class,
            )
        );
    }

    public function getName()
    {
        return 'productItems';
    }
}
