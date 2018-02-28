<?php

namespace App\FilesBundle\Form;

use App\FilesBundle\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType as BaseFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;

use Symfony\Component\OptionsResolver\OptionsResolver;


class FileType extends AbstractType
{
    private $doctrine;
    private $container = null;
    
    public function __construct(RegistryInterface $doctrine, ContainerInterface $container)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;        
    }    
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->id = $id = $builder->getData()->getId();
        $doctrine = $this->doctrine;
        $container = $this->container;        
        
        $builder->add('title',TextType::class, array('label'=>'Tytuł', 'required' => false));
        $builder->add('filePath',BaseFileType::class, array('label'=>'Wybierz plik', 'required' => false));

        /*
         * @TODO addValidator is deprecated and disabled make new solution for symfony3
         */
        $builder->addValidator(new CallbackValidator(function(FormInterface $form)use($container,$doctrine) {
                        
                $fileParams = $container->getParameter("file");
                $mimeType = isset($fileParams["mimetypes"])?$fileParams["mimetypes"]:null;
                $size = isset($fileParams["size"])?$fileParams["size"]:null;                                                
                
                if(isset($form['filePath']) )
                {
                    if($size)                
                    if($form['filePath']->getData()->getSize() > $size*1000000)                         
                        $form['filePath']->addError(new FormError( 'Plik za duży max '.$size.'MB !'));

                    if($form["filePath"]->getData() == true)
                    {
                        if($form['filePath']->getData()->getMimeType() != "image/jpeg")
                            if($form['filePath']->getData()->getMimeType() != "image/jpg")
                                $form['filePath']->addError(new FormError(  'Tylko obrazki (jpg,jpeg) jeżeli ma być dodany znak wodny do obrazka!'));                                  
                    }

                    $result = true;
                    if($mimeType)
                    foreach ($mimeType as $key => $mimes) {
                        if($key == "images"){
                            foreach ($mimes as $k => $value) {
                                if($form['filePath']->getData()->getMimeType() == $value)
                                {
                                     $result *= false;
                                }
                            }
                        }                
                    }

                    if($result)
                        $form['filePath']->addError(new FormError(  'Tylko obrazki (jpg,jpeg, png, gif) !'));                                                          

                }                
            }));
                
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => File::class,
                'required' => false
            )
        );
    }

    public function getName()
    {
        return 'file';
    }
}
