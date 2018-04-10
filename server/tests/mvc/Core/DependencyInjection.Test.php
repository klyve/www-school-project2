<?php


use \MVC\Core\DependencyInjection;
use \MVC\Core\DiInvalidTypeException;



class InjectTestClass1 {
    public $name = 'Hello';

    public function getName() {
        return $this->name;
    }

}

class InjectTestClass2 {
    public $world = 'World';

    public function getWorld() {
        return $this->world;
    }
}

class TestDummyClass {

    public $name = 'Some Random Name';
    public $injected = '';
    public function __construct(InjectTestClass1 $testClass) {
        $this->name = $testClass->getName();
    }

    public function injectFunction(InjectTestClass2 $testClass) {
        $this->name = $testClass->getWorld();
        return $this;
    }

    public function dependencies(InjectTestClass2 $testClass) {
        $this->injected = $testClass->getWorld();
    }
}


class TestParentInjection extends TestDummyClass {

    public function __construct() {

    }
}

class DependencyInjectionTests extends \PHPUnit\Framework\TestCase {

    
    public function testInjectFunction() {
        $testReturn1 = DependencyInjection::inject(function(InjectTestClass1 $testClass) {
            return $testClass->getName();
        });
        
        $this->assertEquals($testReturn1, 'Hello');

        $testReturn2 = DependencyInjection::inject(function(InjectTestClass2 $testClass) {
            return $testClass->getWorld();
        });
        
        $this->assertEquals($testReturn2, 'World');

        $testArray = [
            "object" => function(InjectTestClass2 $testClass) {
                return $testClass->getWorld();
            },
            "method" => null
        ];

        $testReturn3 = DependencyInjection::inject($testArray);
        $this->assertEquals($testReturn3, 'World');
    }

    public function testInjectObject() {
        $testReturn1 = DependencyInjection::inject('TestDummyClass');
        $this->assertEquals($testReturn1->name, 'Hello');
    }

    public function testInjectMethod() {
        $testReturn1 = DependencyInjection::inject([
            'object' => 'TestDummyClass',
            'method' => 'injectFunction'
        ]);
        $this->assertEquals($testReturn1->name, 'World');
    }

    public function testParentInjection() {
        $testReturn1 = DependencyInjection::inject('TestParentInjection');
        $this->assertEquals($testReturn1->injected, 'World');
    }

    public function testInjectArray() {
        $testReturn1 = DependencyInjection::injectArray([
            'object' => 'TestDummyClass',
            'method' => 'injectFunction'
        ]);
        $this->assertEquals($testReturn1->name, 'World');
    }
    
    public function testInjectThrows() {
        $this->expectException(DiInvalidTypeException::class);
        DependencyInjection::inject(1357891);
    }


}