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
  
  // @route POST /user/{userid}/tempfile
  public function postTempfile(Request $req) {

    // @ref copy from okolloen javascript_forelesning 1
    $fname    = $_SERVER['HTTP_X_ORIGINALFILENAME'];      
    $fsize    = $_SERVER['HTTP_X_ORIGINALFILESIZE'];
    $mimetype = $_SERVER['HTTP_X_ORIGINALMIMETYPE'];

    // Read the file from stdin
    $handle = fopen("php://input", 'r');      
    $userid = $req->param('userid');   

    $ROOT    = $_SERVER['DOCUMENT_ROOT'];
    $tempfilename = Hash::md5($userid . $name . $fsize . $mimetype);        // rlkngj..
    $extension = substr($mimetype, strpos($mimetype, '/')+1); // e.g. mp4
    $tempdir = "$ROOT/public/temp/$userid";

    if (!file_exists($tempdir)) {
        @mkdir($tempdir);
    }

    $output = fopen("$tempdir/$tempfilename.$extension", "w");
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

    $res = ['fname'=> "$tempfilename.$extension", 'size'=>$fsize, 'mime'=>$mimetype];

    return Response::statusCode(HTTP_CREATED, $res);
  }

}
