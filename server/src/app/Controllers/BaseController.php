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

use \MVC\Core\View;
use \MVC\Core\Controller;
use \MVC\Http\Request;
use \App\Models;
use \MVC\Core\Session;


class BaseController extends Controller {


    protected $_data = [];

/**
 * @param $view @TODO describe $view
 * @param $data @TODO describe $data
 * @return View::render @TODO describe whats returned
 */
    protected function render($view, $data = []) {
        $combine = array_merge($this->_data, $data);
        return View::render($view, $combine);
    }
/**
 * @param Request $req the request
 * @param Models\UsersModel $user the model containing the user
 */
    public function dependencies(Request $req, Models\UsersModel $user, Models\SubscriptionModel $subs) {
        $sessionId = Session::get('uid');
        if($sessionId) {
            $user->find([
                'id' => $sessionId
            ]);
            $subscriptions = $subs->all([
                "userId" => $user->id
            ]);
            $this->_data['user'] = $user;
            $this->_data['subscriptions'] = $subscriptions;
        }
        // $this->req = ['hello' => 'FROM BASE CONTROLLER'];
    }

}
