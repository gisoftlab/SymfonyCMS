<?php

namespace App\CoreBundle\Libraries;


use App\CoreBundle\Libraries\Cache\ChainAdapter;

/**
 * Class TranslatableRepository
 *
 * This is my translatable repository that offers methods to retrieve results with translations
 */
trait TraitCachingRepository
{
    /**
     * @var ChainAdapter $chainCaching
     */
    protected $chainCaching;

    /**
     * Set chainCaching
     *
     * @param ChainAdapter $chainCaching
     */
    public function setChainCaching($chainCaching)
    {
        $this->chainCaching = $chainCaching;
    }


    /**
     * Get chainCaching
     * @return ChainAdapter
     */
    public function getChainCaching()
    {
        return $this->chainCaching;
    }


    /**
     * Get chainCaching
     *
     * @param string $key
     * @param mixed $value
     * @param array $params
     */
    public function addToCache($key, $value, $params = array())
    {
        $cachedMeta = $this->getChainCaching()->getItem(sprintf($key, $params));
        $cachedMeta->set($value);
        $this->getChainCaching()->save($cachedMeta);
    }
}