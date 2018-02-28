<?php

namespace App\FilesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UploadType extends AbstractType
{
    private $doctrine;
    private $container = null;
    private $id = 0;
    
    public function __construct(RegistryInterface $doctrine = null, ContainerInterface $container = null,$id = null)
    {
        $this->doctrine = $doctrine;
        $this->container = $container;
        $this->id = $id;
    }
    
    public function setID($id)
    {
        $this->id = $id;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        $doctrine = $this->doctrine;
        $container = $this->container;
        $data = $builder->getData();
        $id = isset($data["id"])?$data["id"]:0;

        $builder->add('id',HiddenType::class, array('data' => $id));
        $builder->add('sourceTitle',TextType::class, array('label'=>'Tytuł', 'required' => false));
        $builder->add('sourceFile',FileType::class, array('label'=>'Wybierz plik', 'required' => true));
        $builder->add('waterMark',CheckboxType::class, array('label'=>'Dodaj wodny znak', 'required' => false));
        
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event)use($container, $doctrine) {
            $form = $event->getForm();
            $fileParams = $container->getParameter("file");
            $mimeType = isset($fileParams["mimetypes"]) ? $fileParams["mimetypes"] : null;
            $size = isset($fileParams["size"]) ? $fileParams["size"] : null;         
            if (isset($form['sourceFile'])) {
                if ($form['sourceFile']->getData()) {
                    if ($size)
                        if ($form['sourceFile']->getData()->getSize() > $size * 1000000)
                            $form['sourceFile']->addError(new FormError('Plik za duży max ' . $size . 'MB !'));

                    if ($form["waterMark"]->getData() == true) {
                        if ($form['sourceFile']->getData()->getMimeType() != "image/jpeg")
                            if ($form['sourceFile']->getData()->getMimeType() != "image/jpg")
                                $form['sourceFile']->addError(new FormError('Tylko obrazki (jpg,jpeg) jeżeli ma być dodany znak wodny do obrazka!'));
                    }

                    $result = true;
                    if ($mimeType)
                        foreach ($mimeType as $key => $mimes) {
                            if ($key == "images") {
                                foreach ($mimes as $k => $value) {
                                    if ($form['sourceFile']->getData()->getMimeType() == $value) {
                                        $result *= false;
                                    }
                                }
                            }
                        }

                    if ($result)
                        $form['sourceFile']->addError(new FormError('Tylko obrazki (jpg,jpeg, png, gif) !'));
                }
            }
        });
                
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
               // 'data_class' => \App\PagesBundle\Entity\Category::class,
                'required' => false
            )
        );
    }

    public function getName()
    {
        return 'files_upload';
    }
}
