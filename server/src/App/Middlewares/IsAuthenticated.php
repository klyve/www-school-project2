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
use App\Models\UsersModel;

class IsAuthenticated {

  /**
   * @param $request the request
   * @param $next @TODO describe $next
   * @return redirect @TODO describe whats returned
   */
    public function run($request, $next) {
        $token = $request->token();
        $statusCode = new Error(ErrorCode::get('user.authentication_required'));
        if(!$token) {
            return Response::send($statusCode);
        }

        $userId = $request->token()->userid;
        if(!$userId) {
            return Response::send($statusCode);
        }

        $user = new UsersModel();
        $user->find([
            'id' => $userId
        ]);

        if(!$user->id) {
            return Response::send($statusCode);
        }
        

        // @TODO check if token->userid matches /user/{userid}/
        //  Basically ADD ACCESS CONTROL!

        $next($request);
    }
}
