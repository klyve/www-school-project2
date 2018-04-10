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
use \MVC\Http\Request;
use \MVC\Core\Session;
use \MVC\Core\Config;
use \MVC\Helpers\Hash;
use \App\Models\UsersModel;

class AdminUsersController extends BaseController {
/**
 * @param UsersModel $users the model containing the user
 * @return render @TODO describe whats beeing returned
 */
    public function getDashboard(UsersModel $users) {
        $userList = $users->all([
            'suggestedRole' => 2,
        ]);
        $userGroups = [];
        $configGroups = Config::get('usergroups');
        foreach($configGroups as $key => $value) {
            $userGroups[] = [
                "key" => $key,
                "value" => $value
            ];
        }
        return $this->render('admin/users', ["users" => $userList, "groups" => $userGroups]);
    }
/**
 * @param UsersModel $user the model containing the user
 * @param Request $req the request
 * @return getDashboard @TODO describe whats returned
 */
    public function postUserGroup(UsersModel $user, Request $req) {
        $validation = $req->validate([
            'userid' => 'required',
            'nextGroup' => 'required'
        ]);

        if(!$validation->fails()) {
            $selectedUser = $user->find([
                'id' => $req->input('userid')
            ]);
            if(!$selectedUser->id) {
                return $this->getDashboard(new UsersModel);
            }
            $selectedUser->usergroup = $req->input('nextGroup');
            $selectedUser->suggestedRole = $req->input('nextGroup');
            $selectedUser->save();
            return $this->getDashboard(new UsersModel);
        }else {
            return $this->getDashboard(new UsersModel);
        }

    }
/**
 * @param UsersModel $users the model containing the user
 * @param Request $req the request
 * @return render @TODO describe whats returned
 */
    public function getSearch(UsersModel $users, Request $req) {

        $usersData = [];
        if($req->input('query')) {
            $query = $req->input('query');
            $usersData = $users->search([
                'email' => $query,
                'name' => $query
            ]);
        }
        $searchData = false;
        if(count($usersData) > 0) {
            $searchData = true;
        }

        return $this->render('admin/search', ['users' => $usersData, 'searchData' => $searchData]);
    }
/**
 * @param UsersModel $user the model containing the users
 * @param Request $req the request
 * @return render *TODO describe whats returned
 */
    public function getEditUser(UsersModel $user, Request $req) {
        $user->find([
            'id' => $req->input('userid')
        ]);

        if(!$user->id) {
            // return error view
        }

        return $this->render('admin/user/avatar', ['selected' => $user]);
    }

/**
 * @param UsersModel $user the model containing the user
 * @param Request $req the request
 * @return render @TODO describe whats returned
 */
    public function postEditUser(UsersModel $user, Request $req) {
        $validator = $req->validate([
            'avatar' => 'required|uploaded_file:0,500K,png,jpeg',
        ]);
        $user->find([
            'id' => $req->input('userid')
        ]);

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
        return $this->render('admin/user/avatar', ['selected' => $user]);

    }

    public function getEditUserInfo(UsersModel $user, Request $req) {
        $user->find([
            'id' => $req->input('userid')
        ]);

        if(!$user->id) {
            // return error view
        }

        $userGroups = [];
        $configGroups = Config::get('usergroups');
        foreach($configGroups as $key => $value) {
            $userGroups[] = [
                "key" => $key,
                "value" => $value
            ];
        }

        return $this->render('admin/user/info', ['selected' => $user, "groups" => $userGroups]);

    }

    public function postEditUserInfo(UsersModel $user, Request $req) {
      $user->find([
          'id' => $req->input('userid')
      ]);

      if ($user->id) {
        $user->name = $req->input("name");
        $user->usergroup = $req->input("group");
        $user->save();
      }

      $userGroups = [];
      $configGroups = Config::get('usergroups');
      foreach($configGroups as $key => $value) {
          $userGroups[] = [
              "key" => $key,
              "value" => $value
          ];
      }


      return $this->render('admin/user/info', ['selected' => $user, "groups" => $userGroups]);
    }

}
