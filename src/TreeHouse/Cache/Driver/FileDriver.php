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
        $this->ensureDirectory($cacheDir);

        if (!is_writable($cacheDir)) {
            throw new \LogicException(sprintf('Cache dir is not writeable: "%s"', $cacheDir));
        }

        $this->cacheDir = $cacheDir;
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        $file = $this->getCacheFilePath($key);

        return file_exists($file);
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        $file = $this->getCacheFilePath($key);

        if (!file_exists($file)) {
            return false;
        }

        if (false === $contents = @file_get_contents($file)) {
            throw new \RuntimeException(sprintf('Could not read from cache file: "%s"', $file));
        }

        return $contents;
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value, $ttl = 0)
    {
        $file = $this->getCacheFilePath($key);

        $this->ensureDirectory(pathinfo($file, PATHINFO_DIRNAME));

        // write to tmp file first
        $tmpFile = tempnam($file, 'swap');
        chmod($tmpFile, 0664);

        if (false === file_put_contents($tmpFile, $value)) {
            throw new \RuntimeException(sprintf('Could not write to cache file: "%s"', $file));
        }

        // now move the tmp file to ensure atomic writes
        if (false === $result = rename($tmpFile, $file)) {
            unlink($tmpFile);
        }

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function remove($key)
    {
        $file = $this->getCacheFilePath($key);

        return @unlink($file) || !file_exists($file);
    }

    /**
     * @inheritdoc
     */
    public function clear()
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->cacheDir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isDir()) {
                if (true !== rmdir($file)) {
                    throw new \RuntimeException(sprintf('Could not remove cache directory: "%s"', $file));
                }
            } else {
                if (true !== unlink($file)) {
                    throw new \RuntimeException(sprintf('Could not remove file from cache: "%s"', $file));
                }
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
    private function getCacheFilePath($key)
    {
        $hash = hash('sha256', $key);
        $subdir = substr($hash, 0, 2);
        $filename = substr($hash, 2);

        return $this->cacheDir
            . DIRECTORY_SEPARATOR
            . $subdir
            . DIRECTORY_SEPARATOR
            . $filename
        ;
    }

    /**
     * @param string $directory
     */
    private function ensureDirectory($directory)
    {
        if (is_dir($directory)) {
            return;
        }

        if (false === @mkdir($directory, 0775, true)) {
            throw new \RuntimeException(sprintf('Could not create directory "%s"', $directory));
        }
    }
}
