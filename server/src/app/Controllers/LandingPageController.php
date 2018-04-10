<?php namespace App\Controllers;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   Controllers
 * @package    rewind
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

use \MVC\Core\Session;
use \MVC\Core\View;
use \MVC\Http\Response;

class LandingPageController extends \MVC\Core\Controller {

/**
 * @param Response $res the response
 */
    public function getLandingPage(Response $res) {
        if(Session::get('uid')) {
            $res->redirect('index');
        }

        return View::render('landingpage');
    }

}
