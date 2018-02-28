<?php

namespace App\PagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MetatagType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $builder->add('title',TextType::class, array('label'=>'Tytuł'));
        $builder->add('keywords',TextType::class, array('label'=>'Keywords'));
        $builder->add('description', TextareaType::class, array(
           'label'=>'Opis',
           'required' => false,
           'attr' => array(
               'class' => 'tinymce',
               'tinymce'=>'{"theme":"simple"}')
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
                'data_class' => \App\PagesBundle\Entity\Metatag::class,
            )
        );
    }

    public function getName()
    {
        return 'metatag';
    }
}
