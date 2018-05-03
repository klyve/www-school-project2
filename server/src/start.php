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
require_once __DIR__ . '/App/config.php';

define('APP_ROOT', __DIR__);

// Init configs
MVC\Core\Config::init($config);


if(php_sapi_name() === 'cli') {
    echo "PHP CLI TOOL\n\r";
    MVC\Core\Cli::init($argv);
}else {
    ob_start();
    ini_set('memory_limit','5024M');
    if (function_exists("set_time_limit") == true AND @ini_get("safe_mode") == 0) {
        @set_time_limit(300);
    }

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-OriginalMimetype, X-OriginalFilename, X-OriginalFilesize');
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');

    MVC\Core\Language::init();
    require_once __DIR__ . '/App/Http/errors.php';

    MVC\Http\ErrorCode::init($errors);

    require_once 'App/routes.php';
    MVC\Core\Route::init();
}
