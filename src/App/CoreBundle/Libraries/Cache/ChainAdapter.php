<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\CoreBundle\Libraries\Cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\CacheItem;
use Symfony\Component\Cache\Exception\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\AdapterInterface;


/**
 * Chains several adapters together.
 *
 * Cached items are fetched from the first adapter having them in its data store.
 * They are saved and deleted in all adapters at once.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */

class ChainAdapter  implements AdapterInterface
{
    use HierarchicalCachePoolTrait;

    /**
     * A temporary cache for keys.
     *
     * @type array
     */
    private $keyCache = [];
    /**
     * @type array
     */
    private $adapters = array();
    private $saveUp;

    /**
     * @param CacheItemPoolInterface[] $adapters    The ordered list of adapters used to fetch cached items
     * @param int                      $maxLifetime The max lifetime of items propagated from lower adapters to upper ones
     */
    public function __construct(array $adapters, $maxLifetime = 0)
    {
        if (!$adapters) {
            throw new InvalidArgumentException('At least one adapter must be specified.');
        }

        foreach ($adapters as $adapter) {
            if (!$adapter instanceof CacheItemPoolInterface) {
                throw new InvalidArgumentException(sprintf('The class "%s" does not implement the "%s" interface.', get_class($adapter), CacheItemPoolInterface::class));
            }

            if ($adapter instanceof AdapterInterface) {
                $this->adapters[] = $adapter;
            } else {
                $this->adapters[] = new ProxyAdapter($adapter);
            }
        }

        $this->saveUp = \Closure::bind(
            function ($adapter, $item) use ($maxLifetime) {
                $origDefaultLifetime = $item->defaultLifetime;

                if (0 < $maxLifetime && ($origDefaultLifetime <= 0 || $maxLifetime < $origDefaultLifetime)) {
                    $item->defaultLifetime = $maxLifetime;
                }

                $adapter->save($item);
                $item->defaultLifetime = $origDefaultLifetime;
            },
            null,
            CacheItem::class
        );
    }

    /**
     * GET Iten
     * @param string $key
     * @return CacheItemInterface
     */
    public function getItem($key)
    {
        $storageKey = $this->getHierarchyKey($key,$this->keyCache);
        return $this->getItemByKey($storageKey);
    }

    /**
     * @param $storageKey
     */
    public function saveSubKeys($storageKey){
        foreach ( $this->keyCache as $subKey => $sub) {
            $cached = $this->getItemByKey($subKey);

            if($this->hasItemKey($subKey)){
                $tempItem = $cached->get();
            }

            $tempItem[$storageKey] = $storageKey;
            $cached->set($tempItem);
            $this->save($cached,false);
        }
    }

    /**
     * GET Item Key
     * @param string  $storageKey
     * @return CacheItemInterface
     */
    public function getItemByKey($storageKey)
    {
        $saveUp = $this->saveUp;

        foreach ($this->adapters as $i => $adapter) {
            /**
             * @var CacheItemInterface $item
             */
            $item = $adapter->getItem($storageKey);

            if ($item->isHit()) {
                while (0 <= --$i) {
                    $saveUp($this->adapters[$i], $item);
                }

                return $item;
            }
        }

        return $item;
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(array $keys = array())
    {
        foreach ($keys as $index => $key) {
            $keys[$index] = $this->getHierarchyKey($key);
        }

        return $this->generateItems($this->adapters[0]->getItems($keys), 0);
    }

    /**
     * @param $items
     * @param $adapterIndex
     * @return \Generator
     */
    private function generateItems($items, $adapterIndex)
    {
        $missing = array();
        $nextAdapterIndex = $adapterIndex + 1;
        $nextAdapter = isset($this->adapters[$nextAdapterIndex]) ? $this->adapters[$nextAdapterIndex] : null;

        /**
         * @var CacheItemInterface $item
         */
        foreach ($items as $k => $item) {
            if (!$nextAdapter || $item->isHit()) {
                yield $k => $item;
            } else {
                $missing[] = $k;
            }
        }

        if ($missing) {
            $saveUp = $this->saveUp;
            $adapter = $this->adapters[$adapterIndex];
            $items = $this->generateItems($nextAdapter->getItems($missing), $nextAdapterIndex);

            foreach ($items as $k => $item) {
                if ($item->isHit()) {
                    $saveUp($adapter, $item);
                }

                yield $k => $item;
            }
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasItem($key)
    {
        $key = $this->getHierarchyKey($key);

        return $this->hasItemKey($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasItemKey($key)
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->hasItem($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public function clear()
    {
        $cleared = true;

        foreach ($this->adapters as $adapter) {
            $cleared = $adapter->clear() && $cleared;
        }

        return $cleared;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function deleteItem($key)
    {

        // Get storage key and path key
        $keyCache = array();
        $deleted = true;
        $storageKey = $this->getHierarchyKey($key, $keyCache);
        $subKey = $this->getHierarchySubKey($key);

        if($this->hasItemKey($storageKey)){
            // Clear the local key cache
            foreach ($this->adapters as $adapter) {
                $deleted = $adapter->deleteItem($storageKey) && $deleted;
            }
        }else {
            if ($this->hasItemKey($subKey)) {
                $subKeys = $this->getItemByKey($subKey)->get();
                foreach ($subKeys as $sKey => $ssKey) {
                    // Clear the local root cache
                    foreach ($this->adapters as $adapter) {
                        $deleted = $adapter->deleteItem($sKey) && $deleted;
                    }
                }
            }
        }

        return $deleted;
    }

    /**
     * @param array $keys
     * @return bool
     */
    public function deleteItems(array $keys)
    {
        $deleted = true;
        foreach ($keys as $index => $key) {
            $deleted = $this->deleteItem($key) && $deleted;
        }

        return $deleted;
    }

    /**
     * @param CacheItemInterface $item
     * @param bool $with
     * @return bool
     */
    public function save(CacheItemInterface $item, $with = true)
    {
        $saved = true;
        foreach ($this->adapters as $adapter) {
            $saved = $adapter->save($item) && $saved;
        }

        if($with)
            $this->saveSubKeys($item->getKey());

        return $saved;
    }

    /**
     * @param CacheItemInterface $item
     * @return bool
     */
    public function saveDeferred(CacheItemInterface $item)
    {
        $saved = true;

        foreach ($this->adapters as $adapter) {
            $saved = $adapter->saveDeferred($item) && $saved;
        }

        return $saved;
    }

    /**
     * @return bool
     */
    public function commit()
    {
        $committed = true;

        foreach ($this->adapters as $adapter) {
            $committed = $adapter->commit() && $committed;
        }

        return $committed;
    }
}
