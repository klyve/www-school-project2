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


class ResponseObject {

    private $status;
    private $message;

    public function __construct($status, $message) {
        $this->status = $status;
        $this->message = $message;
    }

    public function getData() {
        return $this->message;
    }

    public function getStatus() {
        return $this->status;
    }

}