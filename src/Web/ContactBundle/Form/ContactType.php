<?php

namespace Web\ContactBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType
{
    /**
     * buildForm
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, array('label' => 'Email', 'required' => true));
        $builder->add('firstname', TextType::class, array('label' => 'Imię', 'required' => true));
        $builder->add('lastname', TextType::class, array('label' => 'Nazwisko', 'required' => true));
        $builder->add('phone', TextType::class, array('label' => 'Telefon', 'required' => true));
        $builder->add('message', TextareaType::class, array('label' => 'Wiadomość', 'required' => true));
    }

    public function getName()
    {
        return 'contact';
    }
}