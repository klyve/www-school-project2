<?php namespace MVC\Http;


class Error {

    private $data;
    private $errCode = 500;

    public function __construct($data, $code = 500) {

        $this->data = $data;
        $this->errCode = $code;
        if(isset($data["code"])) {
            $this->errCode = $data["code"];
        }
    }

    public function getErrorCode() {
        return $this->errCode;
    }

    public function getData() {
        return $this->data;
    }

}