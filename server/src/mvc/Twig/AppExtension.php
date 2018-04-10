<?php namespace MVC\Twig;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   Twig
 * @package    Framework
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use \MVC\Core\Language;
use \MVC\Core\Config;
use \MVC\Core\Route;

class AppExtension extends AbstractExtension {
    public function getFilters() {
        return [
            new TwigFilter('localize', [$this, 'localize']),
            new TwigFilter('link', [$this, 'linkTo']),
            new TwigFilter('userRole', [$this, 'userRole']),
            new TwigFilter('mediaImage', [$this, 'mediaImage']),
            new TwigFilter('mediaVideo', [$this, 'mediaVideo']),
            new TwigFilter('isPageRoute', [$this, 'isPageRoute']),
            new TwigFilter('pageRoute', [$this, 'getPageRoute']),
        ];
    }
    /**
     * @TODO: [localize description]
     * @param  [type] $key [description]
     * @return [type]      [description]
     */
    public function localize($key) {
        $value = Language::get($key);
        if($value) {
            return $value;
        }else {
            return 'Could not find locale key: ' . $key;
        }
    }
/**
 * @TODO: [isPageRoute description]
 * @param  [type]  $key [description]
 * @return boolean      [description]
 */
    public function isPageRoute($key) {
        return Route::isRoute($key);
    }
    /**
     * @TODO: [getPageRoute description]
     * @return [type] [description]
     */
    public function getPageRoute() {
        return Route::getRoute();
    }
/**
 * @TODO: [mediaImage description]
 * @param  [type] $file [description]
 * @return [type]       [description]
 */
    public function mediaImage($file) {
        $fileRoot = Config::get('filepaths.images') . '/';
        return $fileRoot.$file;
    }
    /**
     * @TODO: [mediaVideo description]
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
    public function mediaVideo($file) {
        $fileRoot = Config::get('filepaths.videos') . '/';
        return $fileRoot.$file;
    }
/**
 * @TODO: [linkTo description]
 * @param  [type] $uri [description]
 * @return [type]      [description]
 */
    public function linkTo($uri) {
        if(preg_match( '/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}'.'((:[0-9]{1,5})?\\/.*)?$/i' ,$uri)){
            return $uri;
        }else{
            return 'index.php?page='.$uri;
        }
    }
/**
 * @TODO: [userRole description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
    public function userRole($id) {
        $usergroups = Config::get('usergroups');
        foreach($usergroups as $key => $value) {
            if($value == $id) {
                return $key;
            }
        }
        return 'user';
    }
}
