<?php

namespace App\UserBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;

class ForgottenPassword extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email');
    }

    public function getDefaultOptions()
    {
        return array('required' => false);
    }

    public function getName()
    {
        return 'forgottenpassword';
    }
}
