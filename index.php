<?php

require_once __DIR__ . '/src/start.php';

// class Test2 {
//     public function __construct(Hello $world) {
//         echo "Test 2 init <br />";
//     }

//     public function sayHello() {
//         echo "Hello from world!<br />";
//     }
// }

// class Hello {
//     public function __construct() {
//         echo "Hello init <br />";
//     }

//     public function sayHello() {
//         echo "Hello from Hello!<br />";
//     }
// }


// class Test3 {
//     public function __construct(Test2 $hello) {
//         echo "Test 3 init <br />";
//         $hello->sayHello();
//     }

//     public function sayHello() {
//         echo "Hello from Test3!<br />";
//     }
// }

// class Test {
//     public function __construct(Test2 $testvar) {
//         // $hello = new Test2();
//         print "Test Init<br />";
//         $testvar->sayHello();
//     }
// }



// function DI($className) {
//     $reflection = new ReflectionClass($className);
//     $params = $reflection->getConstructor()->getParameters();

//     $class = null;
//     $args = [];
//     foreach ($params AS $param) {
//         // echo $param . "<br />";
//         if($param->hasType()) {
//             $className = $param->getClass()->name;
//             $args[] = DI($className);
//         }
//     }
    
//     $instance = $reflection->newInstanceArgs($args);
//     return $instance;
// }

// DI('Test');



