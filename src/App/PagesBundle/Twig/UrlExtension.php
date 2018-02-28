<?php

namespace App\PagesBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Component\DependencyInjection\ContainerInterface;
//use CG\Core\ClassUtils;

class UrlExtension extends \Twig_Extension {

    protected static $container;

    public function __construct(ContainerInterface $container) {
        self::$container = $container;
    }

    public static function setContainer($container) {
        self::$container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('mergeParams', array($this, 'mergeParams')),
            new \Twig_SimpleFunction('mergeParamsFromURL', array($this, 'mergeParamsFromURL')),
            new \Twig_SimpleFunction('getAllParameters', array($this, 'getAllParameters')),
        );
    }

    /**
     * @return array|null
     */
    public static function getAllParameters() {
        $request = self::$container->get("request_stack");
        $request = $request->getMasterRequest();

        $params = $request->attributes->all();

        $params = isset($params["_route_params"]) ? $params["_route_params"] : null;
        $query = $request->query->all();
        if ($params)
            $params = array_merge($params, $query);
        else
            $params = $query;

        return $params;
    }

    /**
     * @param null $model
     * @param null $param
     * @return array|null
     */
    public static function mergeParams($model = null, $param = null) {
        $pagesLink = self::getAllParameters();

        if ($model) {
            if (!$param) {
                $pagesLink["id"] = $model->getId();
            } else {
                if (is_array($param)) {
                    $pagesLink = array_merge($pagesLink, $param);
                } else
                    $pagesLink[$param] = $model->getId();
            }
        }else {
            if (is_array($param))
                $pagesLink = array_merge($pagesLink, $param);
        }

        // delete unactive params
        foreach ($pagesLink as $key => $value) {
            if ($value === "")
                unset($pagesLink[$key]);
        }

        return $pagesLink;
    }

    /**
     * @return array|null
     */
    public static function mergeParamsFromURL() {
        return self::getAllParameters();
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'Url';
    }

}
