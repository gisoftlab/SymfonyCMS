<?php

namespace App\BackendBundle\Twig;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuExtension extends \Twig_Extension {

    protected $container;
    protected $doctrine;
    private  $request;

    public function __construct(RegistryInterface $doctrine, ContainerInterface $container) {
        $this->doctrine = $doctrine;        
        $this->container = $container;
    }


    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('hasChildren', array($this, 'hasChildren')),
            new \Twig_SimpleFunction('chackModule', array($this, 'chackModule')),
            new \Twig_SimpleFunction('chackModuleShow', array($this, 'chackModuleShow')),
            new \Twig_SimpleFunction('chackAction', array($this, 'chackAction')),
            new \Twig_SimpleFunction('getModule', array($this, 'getModule')),
            new \Twig_SimpleFunction('getAction', array($this, 'getAction')),
        );
    }

    /**
     * @param $pagesId
     * @return bool
     */
    public function hasChildren($pagesId) {
        
        $children = $this->doctrine->getRepository("AppPagesBundle:Page")->getPagesByParentId($pagesId,false);

        if ($children)
            return true;
        else
            return false;
    }

    /**
     * @param Request|null $request
     * @return mixed
     */
    public function getController(Request $request = null) {
         if(!$request) {
             /**
              * @var $stack RequestStack
              */
             $stack = $this->container->get("request_stack");
             $request = $stack->getCurrentRequest();
         }
         
        return $request->get("_controller");
    }

    /**
     * @param Request $request
     */
    private function setRequest(Request $request) {
         $this->request = $request;
    }

    /**
     * @return array|bool
     */
    private  function getModuleData()
    {
        $data = Array();
        $pathController = explode("::", $this->getController($this->request));
        if(isset($pathController[1])){
            $action = $pathController[1];
            $controller = $pathController[0];
            $controller = explode("Controller\\", $controller);
            
            if(isset($controller[1])){
                $module = $controller[1];            
                $data["action"] = str_replace("Action","",$action);
                $data["module"] = str_replace("Controller","",$module);

                return $data;
            }
        }
        
        return false;                        
    }

    /**
     * @return mixed|string
     */
    public function getModule()
    {
       $data = $this->getModuleData();                
        return isset($data["module"])?$data["module"]:"";
    }

    /**
     * @return mixed|string
     */
    public function getAction()
    {
        $data = $this->getModuleData();                
        return isset($data["action"])?$data["action"]:"";
    }

    /**
     * @param $module
     * @param $parentId
     * @param Request|null $request
     * @return string
     */
    public function chackModule($module, $parentId, Request $request = null) {
        $param = null;

        if(!$request){
            /**
             * @var $stack RequestStack
             */
            $stack = $this->container->get("request_stack");
            $request = $stack->getCurrentRequest();
        }
        
        $this->setRequest($request);
        
        if ($request->attributes->has("ParentId"))            
            $param = $request->attributes->get("ParentId");

        if ($request->attributes->has("id"))            
            if($request->attributes->get("id"))
                $param = $request->attributes->get("id");
            
        if (($this->getModule() == $module) && ($param == null) && ($parentId == null))
            return 'current';
        else if (($this->getModule() == $module) && ($param == $parentId))
            return 'current';
        else
            return 'select';
    }

    /**
     * @param $module
     * @param $parentId
     * @param Request|null $request
     * @return string
     */
   public function chackModuleShow($module, $parentId,Request $request = null) {
        $param = null;
        
        if(!$request){
            /**
             * @var $stack RequestStack
             */
            $stack = $this->container->get("request_stack");
            $request = $stack->getCurrentRequest();
        }

       $this->setRequest($request);
        
        if ($request->attributes->has("ParentId"))            
            $param = $request->attributes->get("ParentId");

        if ($request->attributes->has("id"))            
            if($request->attributes->get("id"))
                $param = $request->attributes->get("id");

        if (($this->getModule() == $module) && ($param == null) && ($parentId == null))
            return 'show';
        else if (($this->getModule() == $module) && ($param == $parentId))
            return 'show';
        else
            return '';
    }

    /**
     * @param $module
     * @param $action
     * @param $parentId
     * @param Request|null $request
     * @return string
     */
   public function chackAction($module, $action, $parentId, Request $request = null) {

        $param = null;
        
        if(!$request){
            /**
             * @var $stack RequestStack
             */
            $stack = $this->container->get("request_stack");
            $request = $stack->getCurrentRequest();
        }

       $this->setRequest($request);
        
        if ($request->attributes->has("ParentId"))            
            $param = $request->attributes->get("ParentId");

        if ($request->attributes->has("id"))            
            if($request->attributes->get("id"))
                $param = $request->attributes->get("id");
        
        if (($this->getModule() == $module)
                && ($this->getAction() == $action)
                && ($param == null)
                && ($parentId == null))
            return 'sub_show';
        else if (($this->getModule() == $module)
                && ($this->getAction() == $action)
                && ($param == $parentId))
            return 'sub_show';
        else
            return '';
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'Menu';
    }

}
