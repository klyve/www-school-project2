<?php

class VideosModel extends \MVC\Core\Model {

  public $table = 'videos';
  public $id;  
  public $name;        // ex: '10 biggest rollercoasters in the world'
  public $description; // ex: 'My trip around the world took me....'
  public $mimetype;    // ex: 'video/mp4, video/ogg'
  public $viewCount;   // ex: '12000000'

  // @TODO: Discuss which format the subtitles should be stored as.
  //        Should it be in a separate table to more easily retrieve 
  //        a subtitle without the need for parsing a subtitle format 
  //        during run-time? Does html natively parse .srt so we don't
  //         have to think about it?   - JSolsvik 11.04.2018
  public $subtitle;   // ex: .srt formatted subtitle. 

  public $userid;     // Foreign key to an instance in the UsersModel
}
