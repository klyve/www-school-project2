<?php namespace App\Middlewares;
/**
 * This is a middleware to check a users (uid) usergroup is 2 (moderator) or greater
 * (admin or owner). if the usergroup level is sufficient, the request will be
 * sent along, else will redirect to index.
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

class IsAdmin {

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
            return $response->redirect('index');
        }else {
            $user = new Model\UsersModel();
            $user->find(['id' => $userId]);

            /** checks if the user doesnt have id or usergroup level is 2 or lower */
            if(!$user->id || $user->usergroup <= 2) {
                return $response->redirect('index');
            }
        }
        /** continues the request */
        $next($request);
    }
}
