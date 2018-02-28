<?php

namespace App\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Twig Extension for Aggregator support.
 *
 * @author damian <info@gisoft.pl>
 */
class AggregatorExtension extends \Twig_Extension {

    /**
     * Container
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Initialize Formater helper
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {

        $this->container = $container;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('renderJs', array($this, 'renderJs'),array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('renderJsInline', array($this, 'renderJsInline'),array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('renderCss', array($this, 'renderCss'),array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('addJavaScriptUrl', array($this, 'addJavaScriptUrl')),
            new \Twig_SimpleFunction('addJavaScriptInline', array($this, 'addJavaScriptInline')),
        );
    }

    /**
     * Render Javascripts
     *
     * @return Response|null
     */
    function renderJs() {

        if ($this->container->has('app.aggregator')) {
            return $this->render("AppCoreBundle:Aggregator:buildJs.html.twig",array(
                "javascripts" => $this->container->get('app.aggregator')->getListOfJs()
            ));
        }

        return null;
    }

    /**
     * Render StyleSheet
     *
     * @return Response|null
     */
    function renderCss() {

        if ($this->container->has('app.aggregator')) {
            return $this->render("AppCoreBundle:Aggregator:buildCss.html.twig",array(
                "stylesheets" => $this->container->get('app.aggregator')->getListOfCss()
            ));
        }

        return null;
    }

    /**
     * Render StyleSheet
     *
     * @return Response|null
     */
    function renderJsInline() {

        if ($this->container->has('app.aggregator')) {
            return $this->render("AppCoreBundle:Aggregator:buildInlineJs.html.twig",array(
                "javascripts" => $this->container->get('app.aggregator')->getListOfInlineJs()
            ));
        }

        return null;
    }

    /**
     * add url to aggregator
     *
     * @param string $source
     * @return null
     */
    function addJavaScriptInline($source) {

        $this->container->get('app.aggregator')->addJsInline($source);

        return null;
    }

    /**
     * add url to aggregator
     *
     * @param string $javascript
     * @return null
     */
    function addJavaScriptUrl($javascript) {

        $this->container->get('app.aggregator')->addJs($javascript);

        return null;
    }


    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    private function render($view, array $parameters = array(), Response $response = null)
    {
        if ($this->container->has('templating')) {
            return $this->container->get('templating')->renderResponse($view, $parameters, $response);
        }

        if (!$this->container->has('twig')) {
            throw new \LogicException('You can not use the "render" method if the Templating Component or the Twig Bundle are not available.');
        }

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($this->container->get('twig')->render($view, $parameters));

        return $response;
    }

    public function getName() {
        return 'aggregator';
    }

}

?>
