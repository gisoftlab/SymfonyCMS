<?php

namespace App\UserBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;

class InitPassword extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldPassword', 'password', array('label' => 'Old password'));
        $builder->add('newPassword', 'repeated', array('type' => 'password', 'first_name' => 'New password', 'second_name' => 'Confirm'));
    }

    public function getDefaultOptions()
    {
        return array('required' => false);
    }

    public function getName()
    {
        return 'initpassword';
    }
}
