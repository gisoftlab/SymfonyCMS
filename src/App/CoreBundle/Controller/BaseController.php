<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\CoreBundle\Controller;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\CoreBundle\Services\MemcachedService as CacheESI;
use Symfony\Component\Validator\Constraints\DateTime;

/**
* Backend Base controller.
*
 * @author Damian Ostraszewski <info@gisoft.pl>   
*/

class BaseController extends Controller {

    /**
     * @var string
     */
    protected $namespace = 'AppCoreBundle';
    /**
     * @var string
     */
    protected $module = '';
    /**
     * @var string
     */
    protected $tplEngine = '.html.twig';

    /**
    * GET Extention Twig URL
    *    
    * @return object $app.twig.url_extension
    */
    public function getUriExtention() {
        return $this->get("app.twig.url_extension");
    }

   /**
    * merge Requested Params
    *
    * @param mixed $model 
    * @param mixed $param    
    * @return string $pagesLink
    */
    public function mergeParams($model = null, $param = null) {
        $uri = $this->getUriExtention();
        return $uri::mergeParams($model, $param);
    }

    /**
    * get all requested params
    *   
    * @return array $params
    */
    public function allParams() {
        $uri = $this->getUriExtention();
        return $uri::getAllParameters();
    }

    /**
    * GET Extention Twig Formater
    *   
    * @return object $app.twig.formater_extension
    */
    public function getFormaterExtention() {
        return $this->get("app_core.twig.formater_extension");
    }

    /**
     * trancate html
     *
     * @param $text
     * @param int $length
     * @param bool $considerHtml
     * @param bool $exact
     * @param string $ending
     * @return mixed
     */
    public function truncateHtml($text, $length = 0, $considerHtml = true, $exact = false, $ending = '...') {
        $formater = $this->getFormaterExtention();
        return $formater->truncateHtml($text, $length, $considerHtml, $exact, $ending);
    }

    /**
    * get URI
    *  
    * @return string 
    */
    public function getUri() {
        $request = $this->getRequest();
        return $request->getBaseUrl() . $request->getPathInfo();
    }

    /**
    * get Referer
    *  
    * @return string 
    */
    public function getReferer() {
        return $this->getRequest()->headers->get('referer');
    }

    /**
     * get Request
     *
     * @return Request
     */
    public function getRequest() {
        $stack = $this->get("request_stack");
        return $stack->getMasterRequest();
    }

    /**
    * get Namespace name
    *      
    * @return string 
    */
    public function getNamespace() {
        return $this->namespace;
    }
    
    /**
    * set Namespace name
    *  
    * @param string $namespace
    */
    public function setNamespace($namespace) {
        $this->namespace = $namespace;
    }

    /**
    * get Module Name
    *      
    * @return string 
    */
    public function getModule() {
        return $this->module;
    }

    /**
    * set Module Name
    *      
    * @param string $module
    */
    public function setModule($module) {
        $this->module = $module;
    }

    /**
     * SET TplEngine  Name
     *
     * @param $tplEngine
     */
    public function setTplEngine($tplEngine) {
        $this->tplEngine = $tplEngine;
    }

    /**
    * GET TplEngine  Name
    *
    * @return string
    */
    public function getTplEngine() {
        return $this->tplEngine;
    }

    /**
     * GET a named entity manager.
     *
     * @return EntityManager
     */
    public function getEm() {
        return $this->get('doctrine')->getManager();
    }

    /**
    * get Repository from service
    *
     * @param null $name
     * @return object
     */
    public function getRepo($name = null) {
        return $this->get("repo." . strtolower((($name) ? $name : $this->getModule())));
    }

    /**
     * get Repository
     *
     * @param $persistentObjectName
     * @param null $persistentManagerName
     * @return mixed
     */
    public function getRepository($persistentObjectName, $persistentManagerName = null)
    {
        return $this->get("doctrine")->getRepository($persistentObjectName, $persistentManagerName);
    }


    /**
     * Chain caching
     *
     * @param int $maxLifetime
     * @return ChainAdapter
     */
    public function getCache($maxLifetime = 0){
        return new ChainAdapter(array(
            $this->get("cache.redis"),
            $this->get("cache.filesystem")
            ),
            $maxLifetime
        );
    }





    /**
     * render template
     *
     * @param $action
     * @param array $parameters
     * @param Response|null $response
     * @return Response
     */
    public function renderTpl($action, array $parameters = array(), Response $response = null) {
        return $this->render($this->getNamespace() . ':' . $this->getModule() . ':' . $action . $this->getTplEngine(), $parameters, $response);
    }

    /**
     * render template
     *
     * @param $action
     * @param array $parameters
     * @param int $maxAgeVal
     * @param string $maxAgeType
     * @param null| DateTime $lastModified
     * @param null| string $module
     * @param null| string $namespace
     * @return Response
     */
    public function renderEsi($action, array $parameters = array(), $maxAgeVal = 1, $maxAgeType = CacheESI::TIME_NEXT_SEC, $lastModified = null, $module = null,  $namespace = null) {

        // DEFAULT No Cachiong
        // $maxAgeVal = 1; $maxAgeType = SEC
        $maxAge = CacheESI::makeTime($maxAgeVal, $maxAgeType);
        $module = ($module)?$module:$this->getModule();
        $namespace = ($namespace)?$namespace:$this->getNamespace();

        if(strrpos($action,":") === false) {
            $response = $this->render($namespace.':'.$module.':'.$action.$this->getTplEngine(), $parameters);
        } else {
            $response = $this->render($action, $parameters);
        }

        // mark the response as either public or private
        $response->setPublic(); // make sure the response is public/cacheable
        // Expiration with Expires Header
        $date = new \DateTime();
        $date->modify("+$maxAge seconds");
        $response->setExpires($date);

        // set the private or shared max age
        //$response->setMaxAge($maxAge);
        $response->setSharedMaxAge($maxAge);
        // set a custom Cache-Control directive
        $response->headers->addCacheControlDirective('must-revalidate', true);


        if($lastModified) {
            $response->setLastModified($date);
        }

        if ($response->isNotModified($this->getRequest())) {
            return $response;
        }

        return $response;
    }

    /**
    * GET service translation
    *          
    * @param string $text    
    * @return string $text 
    */
    public function trans($text) {
        return $this->get('translator')->trans($text);
    }

    /**
    * SET flashMessage to session  service translation
    *          
    * @param string $type    
    * @param string $message
    */
    public function setFlash($type = "message", $message) {
        $this->get('session')->getFlashBag()->set($type, $this->trans($message));
    }

    /**
    * GET User object
    *              
    * @return object User
    */
    public function getUser() {
        $token = $this->get('security.token_storage')->getToken();
        return $token->getUser();
    }

    /**
    * is user login
    *              
    * @return bool
    */
    public function isLogin() {
        if (is_object($this->getUser()))
            return true;
        else
            return false;
    }

    /**
    * is user admin
    *              
    * @return bool
    */
    public function isAdmin() {
        if ($this->getUser()->isAdmin())
            return true;
        else
            return false;
    }

    /**
    * is user cooperator
    *              
    * @return bool
    */
    public function isCooperator() {
        if ($this->getUser()->isCooperator())
            return true;
        else
            return false;
    }

    /**
    * is user has access as user
    *              
    * @return bool
    */
    public function isUsers() {
        if ($this->getUser()->isUsers())
            return true;
        else
            return false;
    }
}
