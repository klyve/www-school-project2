<?php namespace MVC\Helpers;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   Helpers
 * @package    Framework
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */


class Hash {


  private static $saltLength = 22;
/**
 * @TODO: [generateSalt description]
 * @return [type] [description]
 */
  public static function generateSalt() {
    $charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789/\\][{}\'";:?.>,<!@#$%^&*()-_=+|';

    $randString = "";
    for ($i = 0; $i < self::$saltLength; $i++) {
      $randString .= $charset[mt_rand(0, strlen($charset) - 1)];
    }

    return $randString;
  }
/**
 * @TODO: [password description]
 * @param  [type] $password [description]
 * @return [type]           [description]
 */
  public static function password($password) {
    // $salt = self::generateSalt();
    $hashedPwd = password_hash($password, PASSWORD_DEFAULT);

    return $hashedPwd;
    // return [
    //     "salt" => $salt,
    //     "password" => $hashedPwd
    //   ];
  }
/**
 * @TODO: [verify description]
 * @param  [type] $password [description]
 * @param  [type] $hash     [description]
 * @return [type]           [description]
 */
  public static function verify($password, $hash) {
    return password_verify($password, $hash);
  }
/**
 * @TODO: [sha256 description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
  public static function sha256($data) {
    return hash('sha256', $data);
  }
/**
 * @TODO: [sha512 description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
  public static function sha512($data) {
    return hash('sha512', $data);
  }
/**
 * @TODO: [md5 description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
  public static function md5($data) {
    return hash('md5', $data);
  }

}
