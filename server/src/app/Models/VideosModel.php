<?php namespace App\Models;


class VideosModel extends \MVC\Core\Model {

  public $table = 'videos';

  // Primary key
  public $id;          

  // Foreign key
  public $userid;      



  //
  // User defined fields
  //

  // text, max 128 chars, '10 biggest rollercoasters in the world'
  public $title;        

  // text, max 2048 chars, 'My trip around the world took me....'
  public $description; 




  //
  // Server defined fields
  //
  // integer: 0 - MAXINT
  public $viewCount; 

  // example: media/thumbnails/catvideo.png
  //          media/thumbnails/<hash($id . $userid . $name)>.png
  public $filethumbnail;

  // example: media/videos/catvideo.mp4
  //          media/videos/<hash($id . $userid . $name)>.mp4
  public $filevideo;

  // example: media/subtitles/catvideo.srt
  //          media/subtitles/<hash($id . $userid . $name)>.srt
  public $filesubtitle;


  public $exclude = ['id'];

}
