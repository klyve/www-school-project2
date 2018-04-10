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

use MVC\Core\Controller;
use \MVC\Core\View;
use \App\Models\UsersModel;
use \MVC\Http\Request;
use \MVC\Core\Session;
use \MVC\Helpers\Hash;
use \MVC\Helpers\Verify;
use \MVC\Helpers\SQL;
use \MVC\Http\Response;
use \MVC\Core\Config;


class AuthController extends Controller {

/**
 * @param Response $res the response
 * @return View::render @TODO describe whats returned
 */
    public function getLogin(Response $res) {
        if(Session::get('uid')) {
            $res->redirect('index');
        }
        return View::render('login', ['title' => 23]);
    }

/**
 * @param UsersModel $user the model containing the user
 * @param Request $req the request
 * @param Response $res the response
 * @return View::render @TODO describe whats returned
 */
    public function postLogin(UsersModel $user, Request $req, Response $res) {
        $user->find([
            'email' => $req->input('email')
        ]);
        if(Hash::verify($req->input('password'), $user->password)) {
            Session::write('uid', $user->id);
            Session::write('ugroup', $user->usergroup);
            Session::write('language', $user->language);
            Session::write('token', Hash::md5($user->name));
            $res->redirect('index');
        }else {
            return View::render('login', [
                'message' => 'Invalid username or password',
                'email' => $req->input('email'),
            ]);
        }
    }

/**
 * @param $user @TODO describe $user
 */
    public function setLoginSessions($user) {
      Session::write('uid', $user->id);
      Session::write('ugroup', $user->usergroup);
      Session::write('language', $user->language);
      Session::write('token', Hash::md5($user->name));
    }

/**
 * @param Request $req the request
 * @param Response $res the response
 */
    public function getLogout(Request $req, Response $res) {
        Session::destroy();
        $res->redirect('login');
    }
/**
 * @return View::render returns the register view
 */
    public function getRegister() {
        return View::render('register');
    }

/**
 * @param Request $req the request
 * @param UsersModel $user the model containing the user
 * @param Response $res the response
 * @return View::render @TODO describe whats returned
 */
    public function postRegister(Request $req, UsersModel $user, Response $res) {
        $validation = $req->validate([
            'email' => 'required|min:13|max:40',
            'password' => 'required|min:5|max:32',
            'username' => 'required|min:5|max:32',
            'role' => 'required',
        ]);
        if(!$validation->fails()) {
            $id = $user->find(["email" => $req->input("email")])->id;
            if($id) {
                return View::render('register', ["userExists" => true]);
            }

            $user->name = $req->input("username");
            $user->email = $req->input("email");
            $user->password = Hash::password($req->input("password"));
            $user->usergroup = Config::get('usergroups.user');
            $usergroup = 1;
            if(Config::get('usergroups.'.$req->input('role')))
                $usergroup = Config::get('usergroups.'.$req->input('role'));

            $user->suggestedRole = $usergroup;
            $user->save();

            $user->find([
                'email' => $user->email,
            ]);

            $this->setLoginSessions($user);
            $res->redirect('index');
        }else {
            return View::render('register', ['validation' => $validation->errors()->all()]);
        }
    }

}
