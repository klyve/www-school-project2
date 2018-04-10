<?php namespace MVC\Core;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   Core
 * @package    Framework
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

class View {

/**
 * @param $filename @TODO describe this parameter
 * @param $params @TODO describe this parameter
 */
    protected static function renderDefault($filename, $params) {
        $viewPath = __DIR__.'/../../app/'.Config::get('defaults.view.path');
        extract($params);
        if(file_exists($viewPath.'/'.$filename.'.php')) {
            require 'app/view/'.$filename.'.php';
        }else {
            die("View not found");
        }
    }

/**
 * @param $filename @TODO describe this parameter
 * @param $params @TODO describe this parameter
 */
    protected static function renderTwig($filename, $params) {
        $viewPath = __DIR__.'/../../app/'.Config::get('defaults.view.path');
        if(file_exists($viewPath.'/'.$filename.'.html')) {
            $loader = new \Twig_Loader_Filesystem($viewPath);
            $twig = new \Twig_Environment($loader);
            $twig->addExtension(new \MVC\Twig\AppExtension);
            return $twig->render($filename.'.html', $params);
        }else {
            die("View not found");
        }
    }

/**
 * @param $filename @TODO describe this parameter
 * @param $params @TODO describe this parameter
 * @return renderTwig @TODO describe whats returned
 * @return renderDefault @TODO describe whats returned
 */
    public static function render($filename, $params = []) {
        $engine = Config::get('defaults.view.engine');
        switch($engine) {
            case 'twig': {
                return self::renderTwig($filename, $params);
            }break;
            default: {
                return self::renderDefault($filename, $params);
            }
        }
    }
}
