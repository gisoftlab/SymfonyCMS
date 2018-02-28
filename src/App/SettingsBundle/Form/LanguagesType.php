<?php

namespace App\SettingsBundle\Form;

use App\SettingsBundle\Entity\Languages;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguagesType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $ids = null;
         $builder->add('countryName',TextType::class, array('label'=>'Nazwa (Widok)',
              'required' => false
             ));
         $builder->add('culture',TextType::class, array('label'=>'Język (np: pl )',
              'required' => true
             ));
         $builder->add('country', null, array('label' => 'Kraj'
                    , 'required' => true
                    , 'expanded' => false
                    , 'empty_data' => 'Wybierz Kraj'
                    , 'query_builder' => function($er) use ($ids) {
                        $qb = $er->createQueryBuilder('p');
                        $qb->orderBy('p.name', 'ASC');

                        return $qb;
                    }
                ));
         $builder->add('isUsed',null, array('label'=>'Widoczny'));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Languages::class,
                'required' => true
            )
        );
    }

    public function getName()
    {
        return 'languages';
    }
}
