<?php


use \MVC\Http\Response;
class foo {}
class ResponseTests extends \PHPUnit\Framework\TestCase {
  public function testSend() {
    $bar = new foo;
    $array = array("hello" => "world");
    $string = 'FooBar';
    $this->assertEquals(Response::send($array), $array);
    $this->assertEquals(Response::send($string), $string);

    // $this->expectException(ResponseJSONParseException::class);
    // Response::send($bar);
  }


  // public function testJson() {
  //   $bar = new foo;
  //   $this->expectException(DiInvalidTypeException::class);
  //   Response::send($bar);
  // }
}
