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
use \MVC\Http\Response;
use \MVC\Http\Error;
use \MVC\Http\ErrorCode;
use \MVC\Helpers\Auth;
use App\Models\UsersModel;

class SetActiveUser {

  /**
   * @param $request the request
   * @param $next @TODO describe $next
   * @return redirect @TODO describe whats returned
   */
    public function run($request, $next) {
        if(Auth::user()) {
            $next($request);
        }

        $token = $request->token();
        if(!$token) {
            $next($request);
        }
        
        $userId = $request->token()->userid;
        if(!$userId) {
            $next($request);
        }

        $user = new UsersModel();
        $user->find([
            'id' => $userId
        ]);
        Auth::setUser($user);

        $next($request);
    }
}
