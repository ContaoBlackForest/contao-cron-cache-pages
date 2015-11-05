<?php
/**
 * FRAMEWORK
 *
 * Copyright (C) FRAMEWORK
 *
 * @package   brugg-regio-ch
 * @file      config.php
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2015 owner
 */

$GLOBALS['TL_HOOKS']['initializeSystem'][] = array
(
    '\ContaoBlackForest\Cron\Cache\Page\Controller', 'init'
);

$GLOBALS['TL_CRON']['minutely']['cache-mode.build'] = array
(
    '\ContaoBlackForest\Cron\Cache\Page\Controller\Builder', 'parse'
);
