<?php

namespace Web\ProductBundle\Form;

use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormViewInterface;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;
use App\CoreBundle\Library\extendValidation;

class OrderType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('productId', HiddenType::class);
        $builder->add('name', TextType::class, array('label' => 'Imię i Nazwisko:', 'required' => true));
        $builder->add('email', EmailType::class, array('label' => 'Email:', 'required' => true));
        
        $builder->add('dateFrom', TextType::class,array(
       //      'widget' => 'single_text',
             'label' => 'od:',
             'required' => true
        ));
        $builder->add('dateTo', TextType::class,array(
        //     'widget' => 'single_text',
             'label' => 'do:',
             'required' => true
        ));
        $builder->add('description', TextareaType::class, array('label' => 'Dodatkowa informacja:', 'required' => false));
        
        
       $builder->addValidator(new CallbackValidator(function(FormInterface $form) {
                     
                if ($form['name']->getData() == null) {
                    $form['name']->addError(new FormError("Nazwa jest wymagana."));
                }
                
                if ($form['email']->getData() == null) {
                        $form['email']->addError(new FormError("Email jest wymagany."));
               }
//               elseif (!extendValidation::isValidEmail($form['email']->getData())) {
//                        $form['email']->addError(new FormError("Niepoprawny email"));
//                    }

               if ($form['dateFrom']->getData() == null) {
                    $form['dateFrom']->addError(new FormError("Data od jest wymagana."));                                                               
                }elseif(!strtotime($form['dateFrom']->getData()))
                {
                     $form['dateFrom']->addError(new FormError("Data od jest niepoprawna."));    
                    //date("Y-m-d",  strtotime($form['dateFrom']->getData()))                    
                }elseif ($form['dateTo']->getData() == null) {
                    $form['dateTo']->addError(new FormError("Data do jest wymagana."));                                                            
                }elseif(!strtotime($form['dateTo']->getData()))
                {
                     $form['dateFrom']->addError(new FormError("Data do jest niepoprawna."));                        
                }elseif(date("Y-m-d",  strtotime($form['dateFrom']->getData()))  >= date("Y-m-d",  strtotime($form['dateTo']->getData()))){
                    $form['dateTo']->addError(new FormError("Data zwrotu musi być większa od wypożyczenia."));    
                } 

            }));
    }

    public function getName()
    {
        return 'order';
    }
}

