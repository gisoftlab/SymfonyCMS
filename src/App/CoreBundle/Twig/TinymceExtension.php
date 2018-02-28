<?php

namespace App\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use App\CoreBundle\Libraries\Utils\ConfigHelper;

class TinymceExtension extends \Twig_Extension {

    /**
     * Container
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Asset Base Url
     * Used to over ride the asset base url (to not use CDN for instance)
     *
     * @var String
     */
    protected $baseUrl;

    /**
     * Initialize tinymce helper
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * Gets a service.
     *
     * @param string $id The service identifier
     *
     * @return object The associated service
     */
    public function getService($id) {
        return $this->container->get($id);
    }

    /**
     * Get parameters from the service container
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter($name) {
        return $this->container->getParameter($name);
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('tinymce_area_init', array($this, 'tinymceAreaInit'),array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('tinymce_init', array($this, 'tinymceInit'),array('is_safe' => array('html'))),
        );
    }

    /**
     * TinyMce initializations
     * @param array $options
     * @return string
     */
    public function tinymceInit($options = array("style_formats" => "default")) {
        // read config tinymce
        $envParams = ConfigHelper::getInstance(ConfigHelper::CONFIG_TINYMCE)->get("tinymce");

        // settup style_formats as default when some $options are added.
        if (!isset($options["style_formats"]))
            $options["style_formats"] = "default";

        if ($options) {
            foreach ($options as $key => $value) {
                if ($key == "style_formats") {
                    $styleFormats = $envParams[$key][$value];
                    unset($envParams[$key]);
                    $envParams[$key] = $styleFormats;
                } else {
                    $envParams[$key] = $value;
                }
            }
        }

        return json_encode($envParams);
    }

    /**
     * @param null $selector
     * @return mixed
     */
    public function tinymceAreaInit($selector = null, $loadSources = true) {

        $config = $this->getParameter('stfalcon_tinymce.config');
        if ($selector) {
            $config["selector"] = "#".$selector;
        } else{
          return "";
        }

        $config["theme"]["simple"]["verify_html"] = false;
        $config["theme"]["simple"]["remove_trailing_brs"] = false;
        $config["theme"]["simple"]["schema"] = 'html5';

        $config["theme"]["simple"]["force_br_newlines"] = false;
        $config["theme"]["simple"]["force_p_newlines"] = false;
        $config["theme"]["simple"]["forced_root_block"] = '';


        /**
         *  disable deleting tags
         */
        //$config["valid_elements"] = '*[*]';
        $config["theme"]["simple"]["extended_valid_elements"] = 'span';
        $config["theme"]["simple"]["forced_root_block"] = false;

        $this->baseUrl = (!isset($config['base_url']) ? null : $config['base_url']);

        /**
         *  Loading Jquery souyrces
         */
        if($config['include_jquery']){
            $this->getService('app.aggregator')->addJs("//code.jquery.com/jquery-1.11.1.min.js");
        }

        /**
         *  Loading Tinymce Sources
         */
        if($loadSources) {
            if ($config['tinymce_jquery']) {
                $this->getService('app.aggregator')->addJs("bundles/stfalcontinymce/vendor/tinymce/jquery.tinymce.min.js");
                $this->getService('app.aggregator')->addJs("bundles/stfalcontinymce/js/init.jquery.js");
            } else {
                $this->getService('app.aggregator')->addJs("bundles/stfalcontinymce/vendor/tinymce/tinymce.min.js");
                $this->getService('app.aggregator')->addJs("bundles/stfalcontinymce/js/ready.min.js");
                $this->getService('app.aggregator')->addJs("bundles/stfalcontinymce/js/init.standard.js");
            }
        }

        // Asset package name
        $assetPackageName = (!isset($config['asset_package_name']) ? null : $config['asset_package_name']);
        unset($config['asset_package_name']);

        /** @var $assets \Symfony\Component\Templating\Helper\CoreAssetsHelper */
        $assets = $this->getService('assets.packages');

        // Get path to tinymce script for the jQuery version of the editor
        if ($config['tinymce_jquery']) {
            $config['jquery_script_url'] = $assets->getUrl(
                $this->baseUrl.'bundles/stfalcontinymce/vendor/tinymce/tinymce.jquery.min.js',
                $assetPackageName
            );
        }

        // Get local button's image
        foreach ($config['tinymce_buttons'] as &$customButton) {
            if ($customButton['image']) {
                $customButton['image'] = $this->getAssetsUrl($customButton['image']);
            } else {
                unset($customButton['image']);
            }

            if ($customButton['icon']) {
                $customButton['icon'] = $this->getAssetsUrl($customButton['icon']);
            } else {
                unset($customButton['icon']);
            }
        }


        // Update URL to external plugins
        foreach ($config['external_plugins'] as &$extPlugin) {
            $extPlugin['url'] = $this->getAssetsUrl($extPlugin['url']);
        }

        // If the language is not set in the config...
        if (!isset($config['language']) || empty($config['language'])) {
            // get it from the request
            $config['language'] = $this->container->get('request_stack')->getCurrentRequest()->getLocale();
        }


        // If the language is not set in the config...
        if (!isset($config['language']) || empty($config['language'])) {
            // get it from the request
            $config['language'] = $this->getService('request')->getLocale();
        }

        $langDirectory = __DIR__.'/../../Resources/public/vendor/tinymce/langs/';

        // A language code coming from the locale may not match an existing language file
        if (!file_exists($langDirectory.$config['language'].'.js')) {
            unset($config['language']);
        }

        if (isset($config['language']) && $config['language']) {
            // TinyMCE does not allow to set different languages to each instance
            foreach ($config['theme'] as $themeName => $themeOptions) {
                $config['theme'][$themeName]['language'] = $config['language'];
            }
        }

        if (isset($config['theme']) && $config['theme']) {
            // Parse the content_css of each theme so we can use 'asset[path/to/asset]' in there
            foreach ($config['theme'] as $themeName => $themeOptions) {
                if (isset($themeOptions['content_css'])) {
                    // As there may be multiple CSS Files specified we need to parse each of them individually
                    $cssFiles = explode(',', $themeOptions['content_css']);

                    foreach ($cssFiles as $idx => $file) {
                        $cssFiles[$idx] = $this->getAssetsUrl(trim($file)); // we trim to be sure we get the file without spaces.
                    }

                    // After parsing we add them together again.
                    $config['theme'][$themeName]['content_css'] = implode(',', $cssFiles);
                }
            }
        }


        $tinymceConfiguration = preg_replace(
            array(
                '/"file_browser_callback":"([^"]+)"\s*/',
                '/"file_picker_callback":"([^"]+)"\s*/',
                '/"paste_preprocess":"([^"]+)"\s*/',
            ),
            array(
                'file_browser_callback:$1',
                'file_picker_callback:$1',
                '"paste_preprocess":$1',
            ),
            json_encode($config)
        );

        return $this->getService('templating')->render('AppCoreBundle:Tinymce:init.html.twig', array(
            'tinymce_config'     => $tinymceConfiguration,
            'include_jquery'     => $config['include_jquery'],
            'tinymce_jquery'     => $config['tinymce_jquery'],
            'asset_package_name' => $assetPackageName,
            'base_url'           => $this->baseUrl,
        ));
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'tinymce_extention';
    }

    /**
     * Get url from config string
     *
     * @param string $inputUrl
     *
     * @return string
     */
    protected function getAssetsUrl($inputUrl) {
        /** @var $assets \Symfony\Component\Asset\Packages */
        $assets = $this->getService('assets.packages');

        $url = preg_replace('/^asset\[(.+)\]$/i', '$1', $inputUrl);

        if ($inputUrl !== $url) {
            return $assets->getUrl($this->baseUrl . $url);
        }

        return $inputUrl;
    }

}
