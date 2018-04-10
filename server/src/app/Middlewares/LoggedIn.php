<?php namespace App\Middlewares;
/**
 * This is a middleware to check if a user is logged in, if the user is not, it will redirect
 * the user to the login page, if the user is logged in the request will be passed on.
 *
 * PHP version 7
 *
 *
 * @category   Middlewares
 * @package    rewind
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */
use \MVC\Core\Session;

use App\Models as Model;
use \MVC\Http\Response;
class LoggedIn {

  /**
   * @param $request the request
   * @param $next @TODO describe $next
   * @return redirect @TODO describe whats returned
   */
    public function run($request, $next) {
        $userId = Session::get('uid');
        $response = new Response();

        /** checks if we got a uid */
        if(!$userId) {
            $response->redirect('login');
        }else {

            /** continues the request */
            $next($request);
        }
    }
}
