<?php
date_default_timezone_set('UTC');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(dirname(__DIR__)));
define('CONF_DIR', ROOT_DIR.DS.'config'.DS);
define('UBIK_DIR', ROOT_DIR.DS.'Ubik');
define('PUB_DIR', ROOT_DIR.DS.'www');
define('CONTENT', PUB_DIR.DS.'content');
define('TEMPL_DIR', ROOT_DIR.DS.'templates');
define('CACHE_DIR', ROOT_DIR.DS.'cache');
