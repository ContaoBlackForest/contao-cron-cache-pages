<?php
/**
 * FRAMEWORK
 *
 * Copyright (C) FRAMEWORK
 *
 * @package   brugg-regio-ch
 * @file      Builder.php
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2015 owner
 */


namespace ContaoBlackForest\Cron\Cache\Page\Controller;


class Builder
{
    protected $cacheFilePath = 'system/cache/collect-pages/pages.json';
    /**
     * @var \File
     */
    protected $cacheFile;
    protected $cacheUrl;
    protected $timeOut;

    public function parse()
    {
        $this->setTimeOut();

        $this->cacheFile = new \File($this->cacheFilePath, true);
        if (!$this->cacheFile->exists()) {
            return;
        }

        $this->getCacheFromFile();

        if (empty($this->cacheUrl)) {
            return;
        }

        $this->buildCache();
    }

    protected function setTimeOut()
    {
        $timeOut = 90;

        if ($maxExecutionTime = intval(get_cfg_var('max_execution_time'))) {
            $timeOut = $maxExecutionTime;
        }

        $this->timeOut = time() + ($timeOut * 0.8);
    }

    protected function getCacheFromFile()
    {
        $content = $this->cacheFile->getContent();

        $this->cacheUrl = json_decode($content, true);
    }

    protected function buildCache()
    {
        foreach ($this->cacheUrl as $index => $url) {
            $this->cacheUrl($index, $url);

            if (time() >= $this->timeOut) {
                $this->putContentForCacheFile();

                break;
            }
        }

        $this->putContentForCacheFile();
    }

    protected function cacheUrl($index, $url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_exec($ch);

        curl_close($ch);

        unset($this->cacheUrl[$index]);
    }

    protected function putContentForCacheFile()
    {
        $this->cacheFile->truncate();
        $this->cacheFile->putContent($this->cacheFile->path, json_encode($this->cacheUrl));
    }
}
