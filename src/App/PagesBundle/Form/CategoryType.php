<?php

namespace App\PagesBundle\Form;

use App\PagesBundle\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $id = $builder->getData() ->getId();
        
        $builder->add('name',TextType::class, array('label'=>'Nazwa'));
        $builder->add('description', TextareaType::class, array(
           'label'=>'Opis',
           'required' => false,
           'attr' => array('class' => 'tinymce', 'tinymce'=>'{"theme":"simple"}'))
        );                      
                
        $builder->add('parent', null, array('label' => 'Rodzic'
            , 'required' => false
            , 'multiple' => false
            , 'placeholder' => 'Wybierz rodzica'
            , 'empty_data'  => null
            , 'expanded' => false
            , 'query_builder' => function($er) use ($id) {
                $qb = $er->createQueryBuilder('p');
                if ($id) {
                    $qb
                    ->where('p.id <> :id')
                    ->setParameter('id', $id);
                }
                $qb->orderBy('p.root, p.lft', 'ASC');
                return $qb;
            }
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Category::class,
            )
        );
    }

    public function getName()
    {
        return 'category';
    }
}
