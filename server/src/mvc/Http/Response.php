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
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Origin');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE');

        $ret = null;
        $data = self::unpackData($data);
        if(is_array($data) || gettype($data) === "object") {
            $data = self::json($data);
        }
        
        if($data) echo $data;
    }
/**
 * @TODO: [json description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
    public static function json($data) {
        header('content-type: application/json; charset=utf-8');
        
        try {
            $json = json_encode($data);
            return $json;
        } catch(Exception $e) {
          throw new ResponseJSONParseException($e->message);
        }
    }


    public static function unpackData($data = null) {
        if(is_string($data)) {
            return $data;
        }
        if($data instanceof \MVC\Core\Model) {
            $data = $data->exportData();
        }
        if($data instanceof \MVC\Http\Error) {
            http_response_code($data->getErrorCode());
            $data = $data->getData();
        }
        if($data instanceof \MVC\Http\ResponseObject) {
            http_response_code($data->getStatus());
            $data = self::unpackData($data->getData());
        }
        return $data;
    }
/**
 * @TODO: [redirect description]
 * @param  [type] $url [description]
 * @return [type]      [description]
 */
    public static function redirect($url) {
        header('location: index.php?page='.$url);
    }


    public static function statusCode($status, $message = null) {
        return new ResponseObject($status, $message);
    }
}
