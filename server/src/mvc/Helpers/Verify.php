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




class Verify {


/**
 * @TODO: [input description]
 * @param  [type] $template [description]
 * @param  [type] $values   [description]
 * @return [type]           [description]
 */
  public static function input($template, $values) {
    $verified = false;

    $emailMinLength = $template["email"]["length"]["min"];
    $emailMaxLength = $template["email"]["length"]["max"];

    $pwdMinLength = $template["password"]["length"]["min"];
    $pwdMaxLength = $template["password"]["length"]["max"];

    $usernameMinLength = $template["username"]["length"]["min"];
    $usernameMaxLength = $template["username"]["length"]["max"];


    $emailLength = strlen($values["email"]);
    $pwdLength = strlen($values["password"]);
    $usernameLength = strlen($values["email"]);


    if ( isset($values["email"])    &&
         isset($values["username"]) &&
         isset($values["password"]) ) {

      $verified = true;
    } else {
      $verified = false;
    }

    if ($emailLength > $emailMinLength && $emailLength < $emailMaxLength) {
      $verified = true;
    } else {
      $verified = false;
    }

    if ($pwdLength > $pwdMinLength && $pwdLength < $pwdMaxLength) {
      $verified = true;
    } else {
      $verified = false;
    }

    if ($usernameLength > $usernameMinLength && $usernameLength < $usernameMaxLength) {
      $verified = true;
    } else {
      $verified = false;
    }

    return $verified;
  }
}
