<?php

/*
* This file is part of the Sonata package.
*
* (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*
*/

namespace App\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    private $class;

    /**
* @param string $class The User class name
*/
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
    * {@inheritdoc}
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', null, array('required' => false))
            ->add('firstname', null, array('required' => false))
            ->add('lastname', null, array('required' => false))
            ->add('website', null, array('required' => false))
            ->add('locale', null, array('required' => false))
            ->add('timezone', null, array('required' => false))
            ->add('phone', null, array('required' => false))
        ;
    }


    /**
* {@inheritdoc}
*/
    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => $this->class
        );
    }

    /**
* {@inheritdoc}
*/
    public function getName()
    {
        return 'sonata_user_profile';
    }
}