<?php


use \MVC\Helpers\sql;

class sqlTests extends \PHPUnit\Framework\TestCase {
  public function testInsert(){
    $data = array("rating" => 1, "videoId" => "videoID");

    $this->assertEquals(sql::insert($data , 'ratings'), 'INSERT INTO ratings ( rating,videoId) VALUES(:rating,:videoId)');
    $this->assertNotEquals(sql::insert($data , 'fartings'), 'INSERT INTO ratings ( rating,videoId) VALUES(:rating,:videoId)');

  }

  public function testFind() {
    $data = array("rating" => 1);
    $malformedData = array("Rating" => '1');
    $longData = array("rating" => '1', "videoId" => "videoID");
    $emptyData = array();
    $table = 'ratings';

    $this->assertEquals(sql::find($data, $table), 'SELECT * FROM ratings WHERE rating=:rating');
    $this->assertEquals(sql::find($emptyData, $table), 'SELECT * FROM ratings');
    $this->assertEquals(sql::find($longData, $table), 'SELECT * FROM ratings WHERE rating=:rating AND videoId=:videoId');
    $this->assertNotEquals(sql::find($malformedData, $table), 'SELECT * FROM ratings WHERE rating=:rating');

  }
  public function testUpdate() {
    $data = array("rating" => 1);
    $malformedData = array("Rating" => 1);
    $longData = array("rating" => 1, "videoId" => "videoID");
    $emptyData = array();
    $selector = array("videoId" => "videoID");
    $multipleSelector = array("rating" => 1, "videoId" => "3");
    $table = 'ratings';


    $this->assertFalse(sql::update($emptyData, $selector, $table));
    $this->assertEquals(sql::update($data, $selector, $table), 'UPDATE ratings SET rating=:rating WHERE videoId=:videoId');
    $this->assertEquals(sql::update($data, $multipleSelector, $table), 'UPDATE ratings SET rating=:rating WHERE rating=:rating AND videoId=:videoId');
    $this->assertEquals(sql::update($longData, $selector, $table), 'UPDATE ratings SET rating=:rating, videoId=:videoId WHERE videoId=:videoId');
    $this->assertEquals(sql::update($longData, $multipleSelector, $table), 'UPDATE ratings SET rating=:rating, videoId=:videoId WHERE rating=:rating AND videoId=:videoId');

  }

}
