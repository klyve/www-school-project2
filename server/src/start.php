<?php
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   start
 * @package    rewind
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

require_once 'mvc/bootstrap.php';
require_once __DIR__ . '/app/config.php';

define('APP_ROOT', __DIR__);

// Init configs
MVC\Core\Config::init($config);

if(php_sapi_name() === 'cli') {
    echo "PHP CLI TOOL\n\r";
    MVC\Core\Cli::init($argv);
}else {
    MVC\Core\Language::init();

    require_once 'app/routes.php';
    MVC\Core\Route::init();
}
