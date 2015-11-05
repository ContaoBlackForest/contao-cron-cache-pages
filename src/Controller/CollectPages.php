<?php
/**
 * FRAMEWORK
 *
 * Copyright (C) FRAMEWORK
 *
 * @package   brugg-regio-ch
 * @file      CollectPages.php
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2015 owner
 */


namespace ContaoBlackForest\Cron\Cache\Page\Controller;


class CollectPages
{
    protected $cacheUrl = array();
    protected $cacheDirectory = 'system/cache/collect-pages';
    protected $cacheFile = 'system/cache/collect-pages/pages.json';

    public function parse()
    {
        $this->collectCachePages('system/cache/html');

        $this->generate();
    }

    protected function collectCachePages($path)
    {
        $folders = scandir(TL_ROOT . '/' . $path);

        foreach ($folders as $folder) {
            if (in_array($folder, array('.', '..'))) {
                continue;
            }

            if (!is_dir(TL_ROOT . '/' . $path . '/' . $folder)) {
                $url      = $this->getPageUrlFromFile($path . '/' . $folder);
                if (!$url) {
                    continue;
                }

                $checksum = md5($url);

                if (!array_key_exists($checksum, $this->cacheUrl)) {
                    $this->cacheUrl[$checksum] = $url;
                }

                continue;
            }

            if (is_dir(TL_ROOT . '/' . $path . '/' . $folder)) {
                $this->collectCachePages($path . '/' . $folder);
            }
        }
    }

    protected function getPageUrlFromFile($path)
    {
        $file = new \File($path, true);

        if ($file->exists()) {
            $content = $file->getContentAsArray();

            $url = explode('/* ', $content[0])[1];
            $url = explode(' */', $url)[0];

            return $url;
        }

        return null;
    }

    protected function generate()
    {
        if (!is_dir(TL_ROOT . '/' . $this->cacheDirectory)) {
            $this->makeCacheDirectory();
        }

        $file = new \File($this->cacheFile);
        $file->truncate();

        $file->putContent($file->path ,json_encode($this->cacheUrl));

        $automator = new \Automator();
        $automator->purgePageCache();
    }

    protected function makeCacheDirectory()
    {
        \Files::getInstance()->mkdir($this->cacheDirectory);
    }
}
