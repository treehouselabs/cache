<?php

namespace TreeHouse\Cache\Driver;

class FileDriver implements DriverInterface
{
    /**
     * @var string
     */
    private $cacheDir;

    /**
     * @param string $cacheDir
     */
    public function __construct($cacheDir)
    {
        if (!is_dir($cacheDir) || !is_readable($cacheDir)) {
            throw new \LogicException(sprintf('Cache dir does not exist or is not readable: "%s"', $cacheDir));
        }

        $this->cacheDir = $cacheDir;
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        $file = $this->getCacheFile($key);

        return file_exists($file);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        $file = $this->getCacheFile($key);

        if (!file_exists($file)) {
            return false;
        }

        if (!is_readable($file)) {
            throw new \RuntimeException(sprintf('Cache file is unreadable: "%s"', $file));
        }

        if (false === $contents = file_get_contents($file)) {
            throw new \RuntimeException(sprintf('Could not read from cache file: "%s"', $file));
        }

        return $contents;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        $file = $this->getCacheFile($key);

        if (false === file_put_contents($file, $value)) {
            throw new \RuntimeException(sprintf('Could not write to cache file: "%s"', $file));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        $file = $this->getCacheFile($key);

        if (file_exists($file)) {
            return unlink($file);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        $iterator = new \FilesystemIterator($this->cacheDir);

        foreach ($iterator as $file) {
            if (true !== unlink($file)) {
                throw new \RuntimeException(sprintf('Could not remove file from cache: "%s"', $file));
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function addToList($list, $value)
    {
        $listContents = $this->getList($list);

        if (!in_array($value, $listContents)) {
            $listContents[] = $value;
        }

        return $this->set($list, json_encode($listContents));
    }

    /**
     * @inheritdoc
     */
    public function getList($listName)
    {
        $contents = $this->get($listName);

        if (empty($contents)) {
            return [];
        }

        $list = json_decode($contents, true);

        if (!is_array($list)) {
            throw new \RuntimeException(sprintf('Could not decode list: "%s"', $listName));
        }

        return $list;
    }

    /**
     * @inheritdoc
     */
    public function removeFromList($list, $value)
    {
        $listContents = $this->getList($list);

        if (false !== $key = array_search($value, $listContents, true)) {
            unset($listContents[$key]);
        }

        return $this->set($list, json_encode($listContents));
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private function getCacheFile($key)
    {
        return $this->cacheDir . '/' . md5($key);
    }
}
