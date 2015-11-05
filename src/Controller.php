<?php
/**
 * FRAMEWORK
 *
 * Copyright (C) FRAMEWORK
 *
 * @package   brugg-regio-ch
 * @file      Controller.php
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2015 owner
 */


namespace ContaoBlackForest\Cron\Cache\Page;

use ContaoBlackForest\Cron\Cache\Page\Controller\Builder;
use ContaoBlackForest\Cron\Cache\Page\Controller\CollectPages;


/**
 * Class Controller
 *
 * @package ContaoBlackForest\Cron\Cache\Page
 */
class Controller
{
    public function init()
    {
        if (TL_SCRIPT != 'system/cron/cron.php')
        {
            return;
        }

        // Todo set time for collect pages
        if (\Input::get('cache-mode') === 'collect') {
            unset($GLOBALS['TL_CRON']['minutely']['cache-mode.build']);

            $collector = new CollectPages();
            $collector->parse();
        }
    }
}
