<?php

namespace App\CoreBundle\Services;

use Memcached;
use App\CoreBundle\Libraries\Utils\ConfigHelper;
use App\CoreBundle\Twig\FormaterExtension as Formater;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter;

class MemcachedService
{
    /**
     * Container
     *
     * @var ContainerInterface
     */
    protected $container;
    protected $mem;

    private $env = null;
    private $nextYear = 0;
    private $nextMonth = 0;
    private $nextWeek = 0;
    private $nextDay = 0;
    private $nextHour = 0;
    private $nextHHour = 0;
    private $nextMin = 0;
    private $nextSec = 0;
    private $nextNone = 0;

    const SERVER_HOST = "localhost";
    const SERVER_PORT = "11211";

    const TIME_NEXT_YEAR = "nextYear";
    const TIME_NEXT_MONTH = "nextMonth";
    const TIME_NEXT_WEEK = "nextWeek";
    const TIME_NEXT_DAY = "nextDay";
    const TIME_NEXT_HOUR = "nextHour";
    const TIME_NEXT_HHOUR = "nextHHour";
    const TIME_NEXT_MIN = "nextMIN";
    const TIME_NEXT_SEC = "nextSec";
    const TIME_NEXT_NONE = "nextNone";

    const TIME_MIN = 60;
    const TIME_SEC = 1;
    const TIME_NONE = 0;

    private $dateTimes = array();

    const ENV = "local";

    const NAME_DEALER_GET_SUBDCRIPTION = "dealerGetSubdcription";
    const NAME_CMS_CONTENT = "cmscontentGetbycontent";


    /**
     * MemcachedService constructor.
     * @param ContainerInterface $container
     * @param Memcached $mem
     */
    public function __construct(ContainerInterface $container, Memcached $mem)
    {

        $this->container = $container;
        $this->mem = $mem;

        $this->nextYear = 365 * 24 * 60 * 60;
        $this->nextMonth = 30 * 24 * 60 * 60;
        $this->nextWeek = 7 * 24 * 60 * 60;
        $this->nextDay = 24 * 60 * 60;
        $this->nextHour = 60 * 60;
        $this->nextHHour = 30 * 60;
        $this->nextMin = self::TIME_MIN;
        $this->nextSec = self::TIME_SEC;
        $this->nextNone = self::TIME_NONE;

        $this->env = $this->getSettings("memcached_env");
        // set default value if setting is not exist
        $this->env = ($this->env) ? $this->env : self::ENV;

        $this->dateTimes = array(
            self::TIME_NEXT_YEAR => $this->nextYear,
            self::TIME_NEXT_MONTH => $this->nextMonth,
            self::TIME_NEXT_WEEK => $this->nextWeek,
            self::TIME_NEXT_DAY => $this->nextDay,
            self::TIME_NEXT_HOUR => $this->nextHour,
            self::TIME_NEXT_HHOUR => $this->nextHHour,
            self::TIME_NEXT_MIN => $this->nextMin,
            self::TIME_NEXT_SEC => $this->nextSec,
            self::TIME_NEXT_NONE => $this->nextNone,
        );
    }

    /**
     * @return array
     */
    public static function getTimesArr()
    {
        return array(
            self::TIME_NEXT_YEAR => 365 * 24 * 60 * self::TIME_MIN,
            self::TIME_NEXT_MONTH => 30 * 24 * 60 * self::TIME_MIN,
            self::TIME_NEXT_WEEK => 7 * 24 * 60 * self::TIME_MIN,
            self::TIME_NEXT_DAY => 24 * 60 * self::TIME_MIN,
            self::TIME_NEXT_HOUR => 60 * self::TIME_MIN,
            self::TIME_NEXT_HHOUR => 30 * self::TIME_MIN,
            self::TIME_NEXT_MIN => self::TIME_MIN,
            self::TIME_NEXT_SEC => self::TIME_SEC,
            self::TIME_NEXT_NONE => self::TIME_NONE,
        );
    }


    /**
     * @param int $times
     * @param string $name
     * @return mixed
     */
    public static function makeTime($times = 1, $name = self::TIME_NEXT_HOUR)
    {

        $dateTimesEsi = self::getTimesArr();

        return isset($dateTimesEsi[$name]) ? ($dateTimesEsi[$name] * $times) : ($dateTimesEsi[self::TIME_NEXT_HOUR] * $times);
    }

    /**
     * @param int $times
     * @param string $name
     * @return int
     */
    public function getTime($times = 1, $name = self::TIME_NEXT_HOUR)
    {
        return isset($this->dateTimes[$name]) ? time() + ($this->dateTimes[$name] * $times) : time(
            ) + ($this->dateTimes[self::TIME_NEXT_HOUR] * $times);
    }

    /**
     * @param int $timeInSecound
     * @return bool
     */
    public function flush($timeInSecound = 10)
    {
        try {
            $this->mem->flush($timeInSecound);
        } catch (\Exception $ex) {
            return false;
        }

        return false;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function delete($name)
    {
        try {
            $name = Formater::cleanString($name);
            $name = sha1($name);
            //    var_dump("get element = ".$this->env."_".$name);
            if ($result = $this->mem->get($this->env."_".$name)) {
                return unserialize($result);
            }
        } catch (\Exception $ex) {
            return false;
        }

        return false;
    }

    /**
     * @param $name
     * @return bool|mixed
     */
    public function get($name)
    {
        try {
            $name = Formater::cleanString($name);
            $name = sha1($name);
            //    var_dump("get element = ".$this->env."_".$name);
            if ($result = $this->mem->get($this->env."_".$name)) {
                return unserialize($result);
            }
        } catch (\Exception $ex) {
            return false;
        }

        return false;
    }

    /**
     * @param $name
     * @param $value
     * @param string $timeName
     * @param int $times
     */
    public function set($name, $value, $timeName = self::TIME_NEXT_DAY, $times = 1)
    {
        $time = $this->getTime($times, $timeName);
        $name = Formater::cleanString($name);
        $name = sha1($name);
        //  var_dump("set element = ".$this->env."_".$name);
        $this->mem->set($this->env."_".$name, serialize($value), $time);
    }

    /**
     * @param $name
     * @param $element
     * @param $value
     * @param string $timeName
     * @param int $times
     */
    public function setElement($name, $element, $value, $timeName = self::TIME_NEXT_DAY, $times = 1)
    {
        $name = Formater::cleanString($name);
        $element = sha1(Formater::cleanString($element));
        $this->set($name."_".$element, $value, $timeName, $times);
    }

    /**
     * @param $name
     * @param null $element
     * @return bool|mixed
     */
    public function getElement($name, $element = null)
    {
        $name = Formater::cleanString($name);
        if ($name && !$element) {
            return $result = $this->get($name);
        } else {
            if ($name && $element) {
                $element = sha1(Formater::cleanString($element));

                return $this->get($name."_".$element);
            }
        }

        return false;
    }

    /**
     * @param $name
     * @param $element
     */
    public function deleteElement($name, $element)
    {
        $name = Formater::cleanString($name);
        $this->delete($name."_".Formater::cleanString($element));
    }

    /**
     * @param bool $value
     * @return array|bool|mixed|string
     */
    public function getSettings($value = false)
    {
        return ConfigHelper::getInstance(ConfigHelper::CONFIG_SETTINGS)->get($value);
    }

}
