<?php

namespace App\ProductBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Web\PagesBundle\Statics\Page as Page;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\FilesBundle\Form\Type\Upload;

class ProductOldType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $id = $builder->getData()->getId();

        $builder->add('title', TextType::class, array('label' => 'Tytuł'));
        $builder->add('iconPromoted', new Upload($this->doctrine,$this->container));              
//      $builder->add(
//            $builder->create('iconPromoted', 'form', array('by_reference' => false))
//                ->add('name', 'text')
//                ->add('email', 'email')
//         );      
        $builder->add('slug', TextType::class, array('label' => 'Slug', 'required' => false));
        $builder->add('price', TextType::class, array('label' => 'Cena', 'required' => true));
        $builder->add('deposit', TextType::class, array('label' => 'Kaucja', 'required' => true));
        $builder->add('short', TextType::class, array('label' => 'Krótki Opis', 'required' => false));
        $builder->add('description', TextareaType::class, array(
            'label' => 'Opis',
            'required' => false,
            'attr' => array('class' => 'tinymce', 'tinymce' => '{"theme":"advanced"}'))
        );

        $builder->add('page', null, array('label' => 'Kategoria'
            , 'required' => false
            , 'multiple' => false
            , 'empty_value' => 'Wybierz kategorię'
            , 'expanded' => false
            , 'query_builder' => function($er) use ($id) {
                $qb = $er->createQueryBuilder('p');
                if ($id) {
                    $qb
                            ->where('p.root = :id')
                            ->setParameter('id', Page::PAGE_RENTAL);
                }
                $qb->orderBy('p.root, p.lft', 'ASC');
                return $qb;
            }
        ));
        $builder->add('published', null, array('label' => 'Widoczny', 'required' => false));
        $builder->add('promoted', null, array('label' => 'Promowany', 'required' => false));
        $builder->add('metaTitle', TextType::class, array('label' => 'Meta Title', 'required' => false));
        $builder->add('metaKeywords', TextType::class, array('label' => 'Meta Keywords', 'required' => false));
        $builder->add('metaDescription', TextType::class, array('label' => 'Meta Description', 'required' => false));

    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'App\ProductBundle\Entity\Product',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => \App\ProductBundle\Entity\Product::class,
            )
        );
    }

    public function getName() {
        return 'product';
    }

}
