<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use \MVC\Helpers\Hash;
use \MVC\Http\ErrorCode;
use \Datetime;

// HTTP STATUS CODES
const HTTP_OK            = 200;  // Success and returning content
const HTTP_CREATED       = 201;  // Successfull creation
const HTTP_ACCEPTED      = 202;  // Marked for  deletion, not deleted yet
const HTTP_NO_CONTENT     = 204;  // Successfull update
const HTTP_BAD_REQUEST    = 400;
const HTTP_NOT_FOUND      = 404; 
const HTTP_NOT_IMPLMENTED = 501;
const HTTP_INTERNAL_ERROR = 500;


class FilesController extends Controller {
  
  // @route POST /file
  public function postFile(Request $req) {

    // @ref copy from okolloen javascript_forelesning 1
    $ROOT = $_SERVER['DOCUMENT_ROOT'];
    $fname = $_SERVER['HTTP_X_ORIGINALFILENAME'];       // Get extra parameters
    $fsize = $_SERVER['HTTP_X_ORIGINALFILESIZE'];
    $mimetype = $_SERVER['HTTP_X_ORIGINALMIMETYPE'];

    $handle = fopen("php://input", 'r');                // Read the file from stdin

    $output = fopen("$ROOT/public/temp/$fname", "w");
    if(!$output) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Could not open file");
    }

    $contents = '';

    while (!feof($handle)) {                // Read in blocks of 8 KB (no file size limit)
        $contents = fread($handle, 8192);
        fwrite($output, $contents);
    }
    fclose($handle);
    fclose($output);

    $res = ['fname'=>$fname, 'size'=>$fsize, 'mime'=>$mimetype];

    return Response::statusCode(HTTP_CREATED, $res);
  }

}
