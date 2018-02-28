<?php
namespace App\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;


class TagExtension extends \Twig_Extension
{
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
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Gets a service.
     *
     * @param string $id The service identifier
     *
     * @return object The associated service
     */
    public function getService($id)
    {
        return $this->container->get($id);
    }

    /**
     * Get parameters from the service container
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('tag', array($this, 'tag')),
            new \Twig_SimpleFunction('content_tag', array($this, 'content_tag')),
            new \Twig_SimpleFunction('cdata_section', array($this, 'cdata_section')),
            new \Twig_SimpleFunction('comment_as_conditional', array($this, 'comment_as_conditional')),
            new \Twig_SimpleFunction('escape_javascript', array($this, 'escape_javascript')),
            new \Twig_SimpleFunction('escape_once', array($this, 'escape_once')),
            new \Twig_SimpleFunction('fix_double_escape', array($this, 'fix_double_escape')),
            new \Twig_SimpleFunction('_tag_options', array($this, '_tag_options')),
            new \Twig_SimpleFunction('_parse_attributes', array($this, '_parse_attributes')),
            new \Twig_SimpleFunction('get_id_from_name', array($this, 'get_id_from_name')),
            new \Twig_SimpleFunction('_convert_options', array($this, '_convert_options')),
            new \Twig_SimpleFunction('_get_option', array($this, '_get_option')),
            new \Twig_SimpleFunction('get_id_from_name', array($this, 'get_id_from_name')),
            new \Twig_SimpleFunction('stringToArray', array($this, 'stringToArray')),
            new \Twig_SimpleFunction('_convert_options', array($this, '_convert_options')),
            new \Twig_SimpleFunction('literalize', array($this, 'literalize')),
            new \Twig_SimpleFunction('replaceConstants', array($this, 'replaceConstants')),            
        );
    }

    
    public static function tag($name, $options = array(), $open = false) {
        if (!$name) {
            return '';
        }
        return '<' . $name . self::_tag_options($options) . (($open) ? '>' : ' />');
    }

    public static function content_tag($name, $content = '', $options = array()) {
        if (!$name) {
            return '';
        }

        return '<' . $name . self::_tag_options($options) . '>' . $content . '</' . $name . '>';
    }

    public static function cdata_section($content) {
        return "<![CDATA[$content]]>";
    }
    
    
    
/**
     * Wraps the content in conditional comments.
     *
     * @param  string $condition
     * @param  string $content
     *
     * @return string
     *
     * @see http://msdn.microsoft.com/en-us/library/ms537512(VS.85).aspx
     */
    public static function comment_as_conditional($condition, $content) {
        return "<!--[if $condition]>$content<![endif]-->";
    }

    /**
     * Escape carrier returns and single and double quotes for Javascript segments.
     *
     * @param string $javascript
     * @return mixed|string
     */
    public static function escape_javascript($javascript = '') {
        $javascript = preg_replace('/\r\n|\n|\r/', "\\n", $javascript);
        $javascript = preg_replace('/(["\'])/', '\\\\\1', $javascript);

        return $javascript;
    }

    /**
     * Escapes an HTML string.
     *
     * @param  string $html HTML string to escape
     * @return string escaped string
     */
    public static function escape_once($html) {
        // TODO function is not working
        //return fix_double_escape(htmlspecialchars($html, ENT_COMPAT, sfConfig::get('sf_charset')));

        return "";
    }

    /**
     * Fixes double escaped strings.
     *
     * @param  string $escaped HTML string to fix
     * @return string fixed escaped string
     */
    public static function fix_double_escape($escaped) {
        return preg_replace('/&amp;([a-z]+|(#\d+)|(#x[\da-f]+));/i', '&$1;', $escaped);
    }

    public static function _tag_options($options = array()) {
        $options = self::_parse_attributes($options);

        $html = '';
        foreach ($options as $key => $value) {
            $html .= ' ' . $key . '="' . self::escape_once($value) . '"';
        }

        return $html;
    }

    public static function _parse_attributes($string) {
        return is_array($string) ? $string : self::stringToArray($string);
    }

    public static function _get_option(&$options, $name, $default = null) {
        if (array_key_exists($name, $options)) {
            $value = $options[$name];
            unset($options[$name]);
        } else {
            $value = $default;
        }

        return $value;
    }

    /**
     * Returns a formatted ID based on the <i>$name</i> parameter and optionally the <i>$value</i> parameter.
     *
     * This function determines the proper form field ID name based on the parameters. If a form field has an
     * array value as a name we need to convert them to proper and unique IDs like so:
     * <samp>
     *  name[] => name (if value == null)
     *  name[] => name_value (if value != null)
     *  name[bob] => name_bob
     *  name[item][total] => name_item_total
     * </samp>
     *
     * <b>Examples:</b>
     * <code>
     *  echo get_id_from_name('status[]', '1');
     * </code>
     *
     * @param  string $name   field name
     * @param  string $value  field value
     *
     * @return string <select> tag populated with all the languages in the world.
     */
    public static function get_id_from_name($name, $value = null) {
        // check to see if we have an array variable for a field name
        if (strstr($name, '[')) {
            $name = str_replace(array('[]', '][', '[', ']'), array((($value != null) ? '_' . $value : ''), '_', '_', ''), $name);
        }

        return $name;
    }

    /**
     * Converts specific <i>$options</i> to their correct HTML format
     *
     * @param  array $options
     * @return array returns properly formatted options
     */
    public static function _convert_options($options) {
        $options = self::_parse_attributes($options);

        foreach (array('disabled', 'readonly', 'multiple') as $attribute) {
            if (array_key_exists($attribute, $options)) {
                if ($options[$attribute]) {
                    $options[$attribute] = $attribute;
                } else {
                    unset($options[$attribute]);
                }
            }
        }

        return $options;
    }
    
      /**
     * Converts string to array
     *
     * @param  string $string  the value to convert to array
     *
     * @return array
     */
    public static function stringToArray($string) {
        preg_match_all('/
      \s*(\w+)              # key                               \\1
      \s*=\s*               # =
      (\'|")?               # values may be included in \' or " \\2
      (.*?)                 # value                             \\3
      (?(2) \\2)            # matching \' or " if needed        \\4
      \s*(?:
        (?=\w+\s*=) | \s*$  # followed by another key= or the end of the string
      )
    /x', $string, $matches, PREG_SET_ORDER);

        $attributes = array();
        foreach ($matches as $val) {
            $attributes[$val[1]] = self::literalize($val[3]);
        }

        return $attributes;
    }
    
    
  /**
     * Finds the type of the passed value, returns the value as the new type.
     *
     * @param  string $value
     * @param  bool   $quoted  Quote?
     *
     * @return mixed
     */
    public static function literalize($value, $quoted = false) {
        // lowercase our value for comparison
        $value = trim($value);
        $lvalue = strtolower($value);

        if (in_array($lvalue, array('null', '~', ''))) {
            $value = null;
        } else if (in_array($lvalue, array('true', 'on', '+', 'yes'))) {
            $value = true;
        } else if (in_array($lvalue, array('false', 'off', '-', 'no'))) {
            $value = false;
        } else if (ctype_digit($value)) {
            $value = (int) $value;
        } else if (is_numeric($value)) {
            $value = (float) $value;
        } else {
            $value = self::replaceConstants($value);
            if ($quoted) {
                $value = '\'' . str_replace('\'', '\\\'', $value) . '\'';
            }
        }

        return $value;
    }

    /**
     * Replaces constant identifiers in a scalar value.
     *
     * @param  string $value  the value to perform the replacement on
     *
     * @return string the value with substitutions made
     */
    public static function replaceConstants($value) {
        return is_string($value) ? preg_replace_callback('/%(.+?)%/', create_function('$v', 'return sfConfig::has(strtolower($v[1])) ? sfConfig::get(strtolower($v[1])) : "%{$v[1]}%";'), $value) : $value;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'tag_extention';
    } 
}

