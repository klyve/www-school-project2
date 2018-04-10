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
use \MVC\Core\Language;
use \MVC\Core\View;
use \MVC\Core\Config;
use \MVC\Http\Response;
use \MVC\Http\Request;
use \MVC\Helpers\Hash;
use \App\Models as Models;


class SettingsController extends BaseController {


/**
 * @param Request $req the request
 * @param Response $res the response
 * @return render @TODO describe whats returned
 */
  public function getAvatar(Request $req, Response $res) {

    return $this->render('users/settings/avatar');
  }
  /**
   * @param Request $req the request
   * @param UsersModel $user the model containing the user
   * @return render @TODO describe whats returned
   */
  public function postAvatar(Request $req, Models\UsersModel $user) {
    $validator = $req->validate([
      'avatar' => 'required|uploaded_file:0,500K,png,jpeg',
    ]);
    $user->find(["id" => Session::get('uid')]);

    if($user->id && !$validator->fails()) {
      $avatar = $req->getFile('avatar');
      $check = getimagesize($avatar["tmp_name"]);
      if($check) {

      }else {
        die("Could not get image size");
      }
      $imageFileType = strtolower(pathinfo($avatar["name"],PATHINFO_EXTENSION));

      $fileRoot = WWW_ROOT . '/' .  Config::get('filepaths.images') . '/';
      $fileName = Hash::md5($avatar["tmp_name"]).'.'.$imageFileType;
      if (move_uploaded_file($avatar["tmp_name"], $fileRoot.$fileName)) {
          $user->avatar = $fileName;
          $user->save();
      }else {
        die("Could not upload file!");
      }
    }else {
      die("Could not validate file!");
    }
    return $this->render('users/settings/avatar', ["user" => $user]);
  }
  /**
   * @param Request $req the request
   * @param UsersModel $usr the model containing the user
   * @return render @TODO describe whats returned
   */
  public function getName(Request $req, Models\UsersModel $usr) {

    return $this->render('users/settings/name');
  }

  /**
   * @param Request $req the request
   * @param UsersModel $user the model containing the user
   */
  public function postName(Request $req, Models\UsersModel $usr) {
    $user = $usr->find(["id" => Session::get('uid')]);
    if (isset($user)) {
      $user->name = $req->input("name");
      $user->save();
    }
    return $this->render('users/settings/name', ["user" => $usr]);
  }

  /**
   * @param Request $req the request
   * @param UsersModel $usr the model containing the user
   * @return render @TODO describe whats returned
   */
  public function getLanguage(Request $req, Models\UsersModel $usr) {
    $user = $usr->find(["id" => Session::get('uid')]);
    $languages = Config::get('defaults.language.available');

    return $this->render('users/settings/language', ["languages" => $languages]);
  }

  /**
   * @param Request $req the request
   * @param UsersModel $usr the model containing the user
   * @return render @TODO describe whats returned
   */
  public function postLanguage(Request $req, Models\UsersModel $usr) {
    $user = $usr->find(["id" => Session::get('uid')]);
    if (isset($user)) {
      $usr->language = $req->input("language");
      $usr->save();
    }
    Session::write('language', $req->input('language'));
    Language::init();

    $languages = Config::get('defaults.language.available');

    return $this->render('users/settings/language', ["user" => $usr, "languages" => $languages]);
  }

  /**
   * @param Request $req the request
   * @return render @TODO describe whats returned
   */
  public function getPassword(Request $req) {

    return $this->render('users/settings/password');
  }

  /**
   * @param Request $req the request
   * @param UsersModel $usr the model containing the user
   * @return render @TODO describe whats returned
   */
  public function postPassword(Request $req, Models\UsersModel $usr) {

    $user = $usr->find(["id" => Session::get('uid')]);

    if (isset($user)){
      if ($req->input("newPassword") === $req->input("newPasswordConfirm") &&
          Hash::verify($req->input("password"), $usr->password)) {

        $usr->password = Hash::password($req->input("password"));
        $usr->save();

        return $this->render('users/settings/password', ["user" => $usr]);
      } else {
        return $this->render('users/settings/password', ["user" => $usr, "wrongpassword" => "Passwords do not match!"]);
      }
    }
  }
}
