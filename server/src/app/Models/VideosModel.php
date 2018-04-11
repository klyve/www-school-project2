<?php


class VideosModel extends \MVC\Core\Model {

  public $table = 'videos';

  // Primary key
  public $id;          

  // Foreign key
  public $userid;      

  // text, max 128 chars, '10 biggest rollercoasters in the world'
  public $name;        

  // text, max 2048 chars, 'My trip around the world took me....'
  public $description; 

  // integer: 0 - MAXINT
  public $viewCount;   

  // @TODO: Discuss which format the subtitles should be stored as.
  //        Should it be in a separate table to more easily retrieve 
  //        a subtitle without the need for parsing a subtitle format 
  //        during run-time? Does html natively parse .srt so we don't
  //         have to think about it? Should be store the subtitle in a 
  //        file instead, just like with videos and thumbnails ? 
  //                                    - JSolsvik 11.04.2018

  // Maybe .srt formatted subtitle. 
  public $subtitle; 

  public $exclude = ['id'];

    // @NOTE: Each entry in this table implicitly points to two files
    //          (possibly subtitle file also?). 
    //        One video file and a thumbnail file. The filenames are 
    //        based on hash of the metadata in an entry.
    //        Example: videos/<hash($id . $userid . $name)>.mp4
    //                 thumbnails/<hash($id . $userid . $name)>.png
    //                 subtitles/<hash($id . $userid . $name)>.srt
}
