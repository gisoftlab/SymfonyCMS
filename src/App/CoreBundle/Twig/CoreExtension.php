<?php

namespace App\CoreBundle\Twig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use CG\Core\ClassUtils;

class CoreExtension extends \Twig_Extension
{
    protected $loader;
    protected $controller;

    public function __construct(FilesystemLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('code', array($this, 'getCode'),array('is_safe' => array('html'))),
        );
    }

    /**
     * @param $template
     * @return string
     */
    public function getCode($template)
    {
        $controller = htmlspecialchars($this->getControllerCode(), ENT_QUOTES, 'UTF-8');
        $template = htmlspecialchars($this->getTemplateCode($template), ENT_QUOTES, 'UTF-8');

        // remove the code block
        $template = str_replace('{% set code = code(_self) %}', '', $template);

        return <<<EOF
<p><strong>Controller Code</strong></p>
<pre>$controller</pre>

<p><strong>Template Code</strong></p>
<pre>$template</pre>
EOF;
    }

    /**
     * @return string
     */
    protected function getControllerCode()
    {
        $class = get_class($this->controller[0]);
        if (class_exists('CG\Core\ClassUtils')) {
            $class = ClassUtils::getUserClass($class);
        }

        $r = new \ReflectionClass($class);
        $m = $r->getMethod($this->controller[1]);

        $code = file($r->getFileName());

        return '    '.$m->getDocComment()."\n".implode('', array_slice($code, $m->getStartline() - 1, $m->getEndLine() - $m->getStartline() + 1));
    }

    /**
     * @param $template
     * @return string
     */
    protected function getTemplateCode($template)
    {
        return $this->loader->getSource($template->getTemplateName());
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'demo';
    }
}
