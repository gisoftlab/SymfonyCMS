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
use App\FilesBundle\Form\UploadType;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProductType extends AbstractType {

    private $doctrine;
    private $container = null;

    /**
     * @var integer
     */
    private $id = null;
    
    public function __construct(RegistryInterface $doctrine, ContainerInterface $container){
        $this->doctrine = $doctrine;
        $this->container = $container;        
    }
    
    public function setID($id){
        $this->id = $id;
    }
        
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $this->id = $id = $builder->getData()->getId();

        $builder->add('title', TextType::class, array('label' => 'Tytuł'));
        if($this->id){
        $builder->add('iconPromoted', UploadType::class,
                array('label' => 'Zdjecie do promocji', 'required' => false));        
        }
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
            , 'required' => true
            , 'multiple' => false
            , 'empty_value' => 'Wybierz kategorię'
            , 'expanded' => false
            , 'query_builder' => function($er)  {
                $qb = $er->createQueryBuilder('p');
                $qb   ->where('p.root = :id')
                        ->setParameter('id', Page::PAGE_RENTAL);
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
