<?php

namespace App\PagesBundle\Form;


use App\PagesBundle\Entity\Page;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class PageType extends AbstractType {

    /**
     * @var integer
     */
    private $id = null;

    public function setId($id){
        $this->id = $id;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $this->id = $id = $builder->getData()->getId();

        $builder->add('title', TextType::class, array('label' => 'Tytuł'));
        $builder->add('slug', TextType::class, array('label' => 'Slug', 'required' => false));
        $builder->add('uri', TextType::class, array('label' => 'URL', 'required' => false));
        $builder->add('short', TextType::class, array('label' => 'Krótki Opis', 'required' => false));
        $builder->add('description', TextareaType::class, array(
            'label' => 'Opis',
            'required' => false,
            'attr' => array(
                'tinymce' => '{"theme":"advanced"}
                '))
        );

        $builder->add('category', null, array('label' => 'Blok'
            , 'required' => false
            , 'expanded' => false
            , 'placeholder' => 'Wybierz block'
            , 'empty_data'  => null
            , 'query_builder' => function(EntityRepository $er) {
                $qb = $er->createQueryBuilder('p');
                $qb->orderBy('p.root, p.lft', 'ASC');

                return $qb;
            }
        ));

        $builder->add('parent', EntityType::class, array('label' => 'Rodzic'
            , 'class' => Page::class
            , 'label_attr' => array('class'=>'checkbox-inline')
            , 'required' => false
            , 'multiple' => false
            , 'placeholder' => 'Wybierz rodzica'
            , 'empty_data'  => null
            , 'expanded' => false
            , 'query_builder' => function(EntityRepository  $er) use ($id) {
                $qb = $er->createQueryBuilder('p');
                if ($id) {
                    $qb
//                        ->join('p.parent', 'pa')
                        ->where('p.id <> :id')
                        //->where('p.parent is null');
                        ->setParameter('id', $id);
                }

                $qb->orderBy('p.root, p.lft', 'ASC');
                return $qb;
            }
        ));

        $builder->add('published', CheckboxType::class, array(
            'label' => 'Publikowano'
          , 'required' => false
          , 'label_attr' => array('class'=>'checkbox-inline')
        ));
        $builder->add('metaTitle', TextType::class, array('label' => 'Meta Title', 'required' => false));
        $builder->add('metaKeywords', TextType::class, array('label' => 'Meta Keywords', 'required' => false));
        $builder->add('metaDescription', TextType::class, array('label' => 'Meta Description', 'required' => false));


        //$builder ->add('translations', 'a2lix_translations');
    }

    /**
     * {@inheritdoc}
     */

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Page::class,
            )
        );
    }


    public function getName() {
        return "page_";
    }

}
