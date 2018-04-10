<?php


use \MVC\Core\Config as Config;


class ConfigTests extends \PHPUnit\Framework\TestCase {

    public $config = [
        'hello' => 'world',
        'nested' => [
            'key' => 'value',
            'deep' => [
                'item' => [
                    'hello' => 'world'
                ]
            ]
        ]
    ];
    public function testInit() {
        $this->assertTrue(Config::init($this->config));
    }

    public function testClassTrait() {
        $this->assertInstanceOf('\MVC\Core\Config', new Config());
    }

    public function testGet() {
        Config::init($this->config);
        $this->assertEquals(Config::get('hello'), $this->config['hello']);
        $this->assertEquals(Config::get('nested'), $this->config['nested']);
        $this->assertEquals(Config::get('nested.key'), $this->config['nested']['key']);
        $this->assertEquals(Config::get('nested.deep.item.hello'), $this->config['nested']['deep']['item']['hello']);

        $this->assertFalse(Config::get('nested.key.item.hello'));
        $this->assertFalse(Config::get('bogus name'));
    }

    public function testSetKey() {
        Config::init($this->config);
        
        $this->assertTrue(Config::set('someKey', 'SomeValue'));
        $this->assertEquals(Config::get('someKey'), 'SomeValue');

        $this->assertTrue(Config::set('someNestedKey', ['hello' => 'world']));
        $this->assertEquals(Config::get('someNestedKey'), ['hello' => 'world']);
        $this->assertEquals(Config::get('someNestedKey.hello'), 'world');
    }

    public function testIsKey() {
        Config::init($this->config);

        $this->assertTrue(Config::set('someKey', 'helloWorld'));
        $this->assertTrue(Config::is('someKey', 'helloWorld'));
        
        

        $this->assertTrue(Config::set('someOtherKey', true));
        $this->assertTrue(Config::is('someOtherKey', true));
        
        $this->assertFalse(Config::is('someKey', 'WorldHello'));
        $this->assertFalse(Config::is('someOtherKey', false));

        $this->assertTrue(Config::is('nested.key', $this->config['nested']['key']));
    }

    public function testDump() {
        Config::init($this->config);
        $this->assertEquals(Config::dump(), $this->config);
    }

}