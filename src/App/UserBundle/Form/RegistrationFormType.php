<?php

namespace App\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;
use App\UserBundle\Entity\User as User;

class RegistrationFormType extends BaseType {

    private $class;
    protected $_em;

    /**
     * @param string $class The User class name
     */
    public function __construct($class,EntityManager $em) {
        $this->class = $class;
        $this->_em = $em;                        
    }
      
    private function getEM(){
        return $this->_em;;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);
        
        $em = $this->getEM();                

        $builder->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle', 'required' => false, 'pattern' => ''));
        $builder->add('firstname', 'text', array('label' => 'Imię:', 'required' => false));
        $builder->add('lastname', 'text', array('label' => 'Nazwisko:', 'required' => false));
        $builder->add('email', 'email', array('label' => 'E-mail:'));
//        $builder->add('birthday', 'date', array(
//            'label' => 'Data Urodzin:', 
//            'required' => true,            
//            'widget' => 'single_text',
//            'format' => 'yyyy-MM-dd',
//            'attr' => array('class' => 'datePicker')
//        ));
        $builder->add('gender', 'choice', array(
            'label' => 'Płęć',
            'attr' => array('class' => 'select'),
            'multiple' => false,                        
            'property_path' => true,            
            'empty_value' => '-- choose --',
            'choices' => array(                
                User::GENDER_FEMALE => 'Female',
                User::GENDER_MALE => 'Male',
            ),            
            'required' => true
        ));
        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.password'),
            'second_options' => array('label' => 'form.password_confirmation'),
            'required' => false
        ));
          

    

//        $builder->add("t_and_c", "checkbox", array(
//            'label' => 'Akceptuje regulamin',
//            "property_path" => false,
//            'required' => false
//                )
//        );
//
//        $builder->add('recaptcha', 'ewz_recaptcha', array(
//            'attr' => array(
//                'options' => array(
//                    'theme' => 'clean'
//                )
//            )
//        ));



        $builder->addValidator(new CallbackValidator(function(FormInterface $form)use($em) {

            //$userType = $form['user']->getData();


//
//            if (!$form["t_and_c"]->getData()) {
//                //$form['t_and_c']->addError(new FormError('Please accept the terms and conditions in order to register'));
//                $form['t_and_c']->addError(new FormError('proszę zaakceptować regulamin'));
//            }
        }));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => $this->class,
                'intention' => 'registration',
            )
        );
    }

    public function getName() {
        return 'app_user_registration';
    }

}