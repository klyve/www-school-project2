<?php namespace MVC\Http;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   HTTP
 * @package    Framework
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

class ResponseException extends \Exception {}
class ResponseJSONParseException extends ResponseException {}


class Response {

/**
 * @TODO: [send description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
    public static function send($data) {
        if(is_array($data) || gettype($data) === "object") {
            echo self::json($data);
            return $data;
        }
        if(is_string($data)) {
            echo $data;
            return $data;
        }
    }
/**
 * @TODO: [json description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
    public static function json($data) {
        try {
            $json = json_encode($data);
            return $json;
        } catch(Exception $e) {
          throw new ResponseJSONParseException($e->message);
        }
    }
/**
 * @TODO: [redirect description]
 * @param  [type] $url [description]
 * @return [type]      [description]
 */
    public static function redirect($url) {
        header('location: index.php?page='.$url);
    }
}
