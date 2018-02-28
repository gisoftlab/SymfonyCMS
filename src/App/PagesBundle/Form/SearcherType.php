<?php

namespace App\PagesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class SearcherType extends AbstractType
{
    
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;               
    }
    
    private function getMainPages()
    {
        $choices = array();
        $pages = $this->doctrine->getRepository('AppPagesBundle:Page')->retrivateByParent();      
        
        foreach ($pages as $page) {
//            $choices[$page->getId()] = $page->__toString();
            $choices[$page->__toString()] = $page->getId();
        }

        return $choices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search',TextType::class , array(
            'label'=>'Szulaj:',
            'required' => false
        ));

        $builder->add('mainpages', ChoiceType::class, array(
            'label' => 'Strony:',
            'required' => false ,
            'expanded' => false ,
            'placeholder' => 'Wybierz' ,
            'empty_data'  => null ,
            'choices' => $this->getMainPages()
        ));
    }

 

    public function getName()
    {
        return 'searcher';
    }
}
