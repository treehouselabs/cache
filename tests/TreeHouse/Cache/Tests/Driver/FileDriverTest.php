<?php

namespace TreeHouse\Cache\Tests\Driver;

use TreeHouse\Cache\Driver\FileDriver;

class FileDriverTest extends DriverTest
{
    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        $dir = $this->getCacheDir();
        if (is_dir($dir)) {
            foreach (new \FilesystemIterator($dir) as $file) {
                unlink($file);
            }

            rmdir($dir);
        }

        mkdir($dir);

        parent::setUp();
    }

    /**
     * @inheritdoc
     */
    protected function getCacheDriver()
    {
        return new FileDriver($this->getCacheDir());
    }

    /**
     * @return string
     */
    protected function getCacheDir()
    {
        return sys_get_temp_dir() . '/php-cache';
    }
}
