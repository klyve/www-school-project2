<?php namespace App\Controllers;

use \MVC\Core\Controller;
use \MVC\Http\Request;
use \MVC\Http\Response;
use \MVC\Http\Error;
use \MVC\Helpers\Hash;
use \MVC\Helpers\File;
use \MVC\Http\ErrorCode;
use \Datetime;
use \App\Models\TempVideosModel;

// HTTP STATUS CODES
const HTTP_OK            = 200;  // Success and returning content
const HTTP_CREATED       = 201;  // Successfull creation
const HTTP_ACCEPTED      = 202;  // Marked for  deletion, not deleted yet
const HTTP_NO_CONTENT     = 204;  // Successfull update
const HTTP_BAD_REQUEST    = 400;
const HTTP_NOT_FOUND      = 404; 
const HTTP_NOT_IMPLMENTED = 501;
const HTTP_INTERNAL_ERROR = 500;

const DS = DIRECTORY_SEPARATOR;

class FilesController extends Controller {
  
     /**
     * @api {Delete} /tempfile Post new file to temp folder
     * @apiName Post file to temp folder.
     * @apiGroup File
     * @apiPermission user
     * 
     * 
     * @apiSuccess {String} data Success message
     * 
     * @apiSuccessExample {json} Post file.
     *      HTTP/1.1 201 Created
     *      {
     *          data: {
     *              id: 1,
     *              userid: 1,
     *              fname: "fileName",
     *              size: 100,
     *              mime: "mp4"
     *          }
     *      }
     * 
     */
  // @route POST /user/{userid}/tempfile
  public function postTempfile(Request $req, TempVideosModel $tmpVid) {

    // Read the file from stdin
    $handle = fopen("php://input", 'r');      
    $userid = $req->token()->userid;

    $extension = ".mp4";
    $tempfilename = Hash::md5($userid . ".dsf2$45/*^s.." . microtime() ) . $extension;
    $tempdir =  WWW_ROOT.DS."public".DS."temp".DS. $userid;

    File::makeDirIfNotExist($tempdir);

    $output = File::openToWrite("$tempdir".DS."$tempfilename");
    if(!$output) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Could not open file");
    }

    $contents = '';

    while (!feof($handle)) {                // Read in blocks of 8 KB (no file size limit)
        $contents = fread($handle, 128);
        fwrite($output, $contents);
    }
    fclose($handle);
    fclose($output);

    $res = ['fname'=> "$tempfilename", 'message' => 'File uploaded succesfully to temp storage'];
    

    // TempVideos(fname, size, mine, userid);
    // Return TempVideos
    $lastinsertid = $tmpVid->create([
      'fname' => $tempfilename,
      'userid' => $userid
    ]);

    if(!$lastinsertid) {
        return Response::statusCode(HTTP_INTERNAL_ERROR, "Could not create tempvideo entry");
    }

    return Response::statusCode(HTTP_CREATED, ["id" => $lastinsertid]);
  }

}
