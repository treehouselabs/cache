<?php

namespace TreeHouse\Cache\Adapter;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\CacheProvider;
use TreeHouse\Cache\CacheInterface;

class DoctrineAdapter extends CacheProvider
{
    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @inheritdoc
     */
    protected function doFetch($id)
    {
        return $this->cache->get($id);
    }

    /**
     * @inheritdoc
     */
    protected function doContains($id)
    {
        return $this->cache->has($id);
    }

    /**
     * @inheritdoc
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        return $this->cache->set($id, $data, $lifeTime);
    }

    /**
     * @inheritdoc
     */
    protected function doDelete($id)
    {
        return $this->cache->remove($id);
    }

    /**
     * @inheritdoc
     */
    protected function doFlush()
    {
        throw new \RuntimeException('Not supported');
    }

    /**
     * @inheritdoc
     */
    protected function doGetStats()
    {
        return [
            Cache::STATS_HITS => false,
            Cache::STATS_MISSES => false,
            Cache::STATS_UPTIME => false,
            Cache::STATS_MEMORY_USAGE => false,
            Cache::STATS_MEMORY_AVAILABLE => false,
        ];
    }
}
